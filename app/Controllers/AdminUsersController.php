<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use function App\admin_view;
use function App\verify_csrf;
use function App\handleUploadFromForm;

class AdminUsersController {
    private static function requireAdmin(): void {
        \App\requireAdmin();
    }

    public static function index(): void {
        self::requireAdmin();

        $users = User::all();

        echo admin_view('users_list', [
            'title' => 'Users',
            'users' => $users
        ]);
    }

    public static function create(): void {
        self::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::store();
            return;
        }

        echo admin_view('users_form', [
            'title' => 'Add New User',
            'user' => null
        ]);
    }

    private static function store(): void {
        verify_csrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $role = $_POST['role'] ?? 'editor';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $bio = trim($_POST['bio'] ?? '');

        $errors = [];

        // Validation
        if (empty($name)) $errors[] = 'Name is required';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
        if (empty($username) || strlen($username) < 3) $errors[] = 'Username must be at least 3 characters';
        if (empty($password) || strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';
        if ($password !== $passwordConfirm) $errors[] = 'Passwords do not match';

        // Check if email already exists
        if (User::findByEmail($email)) {
            $errors[] = 'Email already registered';
        }

        // Check if username already exists
        if (User::findByUsername($username)) {
            $errors[] = 'Username already taken';
        }

        // Handle avatar upload
        $avatar = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $result = handleUploadFromForm('avatar');
            if ($result) {
                $avatar = $result;
            } else {
                $errors[] = 'Failed to upload avatar. Please ensure the file is a valid image (JPG, PNG, GIF, or WebP) under 5MB.';
            }
        }

        if (!empty($errors)) {
            echo admin_view('users_form', [
                'title' => 'Add New User',
                'user' => null,
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email,
                    'username' => $username,
                    'role' => $role,
                    'is_active' => $isActive,
                    'bio' => $bio
                ]
            ]);
            return;
        }

        // Create user
        User::create([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'is_active' => $isActive,
            'avatar' => $avatar,
            'bio' => $bio
        ]);

        header('Location: /admin/users?success=created');
        exit;
    }

    public static function edit(int $id): void {
        self::requireAdmin();

        $user = User::findById($id);
        if (!$user) {
            header('Location: /admin/users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::update($id);
            return;
        }

        echo admin_view('users_form', [
            'title' => 'Edit User',
            'user' => $user
        ]);
    }

    private static function update(int $id): void {
        verify_csrf();

        $user = User::findById($id);
        if (!$user) {
            header('Location: /admin/users');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $role = $_POST['role'] ?? 'editor';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $bio = trim($_POST['bio'] ?? '');

        $errors = [];
        $updateData = [];

        // Validation
        if (empty($name)) {
            $errors[] = 'Name is required';
        } else {
            $updateData['name'] = $name;
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        } else {
            // Check if email changed and is already taken
            if ($email !== $user['email']) {
                $existingUser = User::findByEmail($email);
                if ($existingUser && $existingUser['id'] !== $id) {
                    $errors[] = 'Email already in use by another user';
                } else {
                    $updateData['email'] = $email;
                }
            }
        }

        if (empty($username) || strlen($username) < 3) {
            $errors[] = 'Username must be at least 3 characters';
        } else {
            // Check if username changed and is already taken
            if ($username !== $user['username']) {
                $existingUser = User::findByUsername($username);
                if ($existingUser && $existingUser['id'] !== $id) {
                    $errors[] = 'Username already taken';
                } else {
                    $updateData['username'] = $username;
                }
            }
        }

        // Handle avatar upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $result = handleUploadFromForm('avatar');
            if ($result) {
                $updateData['avatar'] = $result;
            } else {
                $errors[] = 'Failed to upload avatar. Please ensure the file is a valid image (JPG, PNG, GIF, or WebP) under 5MB.';
            }
        }

        // Password change is optional
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters';
            } elseif ($password !== $passwordConfirm) {
                $errors[] = 'Passwords do not match';
            } else {
                $updateData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            }
        }

        // Don't allow users to change their own role or status (if using new user session)
        $currentUserId = $_SESSION['user']['id'] ?? null;
        $isEditingSelf = $currentUserId && ($id === (int)$currentUserId);

        if (!$isEditingSelf) {
            $updateData['role'] = $role;
            $updateData['is_active'] = $isActive;
        }

        $updateData['bio'] = $bio;

        if (!empty($errors)) {
            echo admin_view('users_form', [
                'title' => 'Edit User',
                'user' => $user,
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email,
                    'username' => $username,
                    'role' => $role,
                    'is_active' => $isActive,
                    'bio' => $bio
                ]
            ]);
            return;
        }

        // Update user
        User::update($id, $updateData);

        header('Location: /admin/users?success=updated');
        exit;
    }

    public static function delete(int $id): void {
        self::requireAdmin();
        verify_csrf();

        // Don't allow deleting yourself (if using new user session)
        $currentUserId = $_SESSION['user']['id'] ?? null;
        if ($currentUserId && $id === (int)$currentUserId) {
            header('Location: /admin/users?error=self_delete');
            exit;
        }

        User::delete($id);
        header('Location: /admin/users?success=deleted');
        exit;
    }
}
