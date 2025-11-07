<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use function App\admin_view;
use function App\verify_csrf;
use function App\handleUploadFromForm;

class AdminProfileController {
    public static function show(): void {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $userId = (int)$_SESSION['user']['id'];
        $user = User::findById($userId);

        if (!$user) {
            header('Location: /login');
            exit;
        }

        echo admin_view('profile', [
            'title' => 'My Profile',
            'user' => $user
        ]);
    }

    public static function update(): void {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        verify_csrf();

        $userId = (int)$_SESSION['user']['id'];
        $user = User::findById($userId);

        if (!$user) {
            header('Location: /login');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $newPasswordConfirm = $_POST['new_password_confirm'] ?? '';

        $errors = [];
        $updateData = [];

        // Validate name
        if (empty($name)) {
            $errors[] = 'Name is required';
        } else {
            $updateData['name'] = $name;
        }

        // Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        } else {
            // Check if email changed and is already taken
            if ($email !== $user['email']) {
                $existingUser = User::findByEmail($email);
                if ($existingUser && $existingUser['id'] !== $userId) {
                    $errors[] = 'Email already in use by another user';
                } else {
                    $updateData['email'] = $email;
                }
            }
        }

        // Validate username
        if (empty($username) || strlen($username) < 3) {
            $errors[] = 'Username must be at least 3 characters';
        } else {
            // Check if username changed and is already taken
            if ($username !== $user['username']) {
                $existingUser = User::findByUsername($username);
                if ($existingUser && $existingUser['id'] !== $userId) {
                    $errors[] = 'Username already taken';
                } else {
                    $updateData['username'] = $username;
                }
            }
        }

        // Bio is optional
        $updateData['bio'] = $bio;

        // Handle avatar upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $result = handleUploadFromForm('avatar');
            if (isset($result['error'])) {
                $errors[] = $result['error'];
            } else {
                $updateData['avatar'] = $result['url'];
            }
        }

        // Handle password change
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                $errors[] = 'Current password is required to set a new password';
            } elseif (!password_verify($currentPassword, $user['password_hash'])) {
                $errors[] = 'Current password is incorrect';
            } elseif (strlen($newPassword) < 6) {
                $errors[] = 'New password must be at least 6 characters';
            } elseif ($newPassword !== $newPasswordConfirm) {
                $errors[] = 'New passwords do not match';
            } else {
                $updateData['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }
        }

        if (!empty($errors)) {
            // Reload user data to get latest
            $user = User::findById($userId);
            echo admin_view('profile', [
                'title' => 'My Profile',
                'user' => $user,
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email,
                    'username' => $username,
                    'bio' => $bio
                ]
            ]);
            return;
        }

        // Update user
        User::update($userId, $updateData);

        // Update session data
        $_SESSION['user']['name'] = $updateData['name'] ?? $_SESSION['user']['name'];
        $_SESSION['user']['email'] = $updateData['email'] ?? $_SESSION['user']['email'];
        $_SESSION['user']['username'] = $updateData['username'] ?? $_SESSION['user']['username'];

        // Reload user and show success
        $user = User::findById($userId);
        echo admin_view('profile', [
            'title' => 'My Profile',
            'user' => $user,
            'success' => 'Profile updated successfully!'
        ]);
    }
}
