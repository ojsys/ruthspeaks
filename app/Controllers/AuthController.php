<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use function App\view;
use function App\csrf_field;
use function App\verify_csrf;

class AuthController {
    public static function showRegister(): void {
        if (isset($_SESSION['user'])) {
            header('Location: /admin');
            exit;
        }

        echo view('layout', [
            'title' => 'Register',
            'content' => view('auth/register', [])
        ]);
    }

    public static function register(): void {
        verify_csrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

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

        if (!empty($errors)) {
            echo view('layout', [
                'title' => 'Register',
                'content' => view('auth/register', [
                    'errors' => $errors,
                    'name' => $name,
                    'email' => $email,
                    'username' => $username
                ])
            ]);
            return;
        }

        // Create user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Debug logging
        error_log("=== REGISTRATION DEBUG ===");
        error_log("Plain password: " . $password);
        error_log("Hashed password: " . $hashedPassword);
        error_log("Hash length: " . strlen($hashedPassword));
        error_log("==========================");

        $userId = User::create([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password_hash' => $hashedPassword,
            'role' => 'editor',
            'is_active' => 1
        ]);

        // Log user in
        $user = User::findById($userId);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        User::updateLastLogin($userId);

        header('Location: /admin');
        exit;
    }

    public static function showLogin(): void {
        if (isset($_SESSION['user'])) {
            header('Location: /admin');
            exit;
        }

        echo view('layout', [
            'title' => 'Login',
            'content' => view('auth/login', [])
        ]);
    }

    public static function login(): void {
        verify_csrf();

        $emailOrUsername = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        $errors = [];

        if (empty($emailOrUsername)) $errors[] = 'Email or username is required';
        if (empty($password)) $errors[] = 'Password is required';

        if (!empty($errors)) {
            echo view('layout', [
                'title' => 'Login',
                'content' => view('auth/login', [
                    'errors' => $errors,
                    'email' => $emailOrUsername
                ])
            ]);
            return;
        }

        // Find user by email or username
        $user = User::findByEmail($emailOrUsername);
        if (!$user) {
            $user = User::findByUsername($emailOrUsername);
        }

        // Debug logging
        error_log("=== LOGIN DEBUG ===");
        error_log("User found: " . ($user ? 'YES' : 'NO'));
        if ($user) {
            error_log("User ID: " . $user['id']);
            error_log("User email: " . $user['email']);
            error_log("Password hash exists: " . (isset($user['password_hash']) ? 'YES' : 'NO'));
            error_log("Password hash value: " . ($user['password_hash'] ?? 'NULL'));
            error_log("Password hash length: " . (isset($user['password_hash']) ? strlen($user['password_hash']) : '0'));
            error_log("Password from form: " . $password);
            error_log("Password verify result: " . (password_verify($password, $user['password_hash']) ? 'SUCCESS' : 'FAILED'));
        }
        error_log("==================");

        if (!$user || !password_verify($password, $user['password_hash'])) {
            echo view('layout', [
                'title' => 'Login',
                'content' => view('auth/login', [
                    'errors' => ['Invalid credentials'],
                    'email' => $emailOrUsername
                ])
            ]);
            return;
        }

        // Check if user is active
        if (!$user['is_active']) {
            echo view('layout', [
                'title' => 'Login',
                'content' => view('auth/login', [
                    'errors' => ['Your account has been deactivated'],
                    'email' => $emailOrUsername
                ])
            ]);
            return;
        }

        // Set session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        User::updateLastLogin((int)$user['id']);

        // Redirect to intended page or admin
        $redirect = $_SESSION['intended'] ?? '/admin';
        unset($_SESSION['intended']);
        header('Location: ' . $redirect);
        exit;
    }

    public static function logout(): void {
        unset($_SESSION['user']);
        unset($_SESSION['admin']);
        session_destroy();
        header('Location: /login');
        exit;
    }
}
