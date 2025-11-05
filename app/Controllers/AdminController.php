<?php
declare(strict_types=1);

namespace App\Controllers;

use function App\view;
use function App\e;
use const App\ADMIN_EMAIL;
use const App\ADMIN_PASSWORD_HASH;

class AdminController {
    public static function login(): void {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = (string)($_POST['password'] ?? '');
            if (strcasecmp($email, ADMIN_EMAIL) === 0 && password_verify($password, ADMIN_PASSWORD_HASH)) {
                $_SESSION['admin'] = ['email' => $email];
                header('Location: /admin');
                exit;
            }
            $error = 'Invalid credentials.';
        }
        echo view('layout', [
            'title' => 'Admin Login',
            'content' => view('admin/login', ['error' => $error ?? null])
        ]);
    }

    public static function logout(): void {
        unset($_SESSION['admin']);
        header('Location: /admin/login');
        exit;
    }

    public static function dashboard(): void {
        if (!isset($_SESSION['admin'])) { header('Location: /admin/login'); exit; }
        echo view('layout', [
            'title' => 'Admin',
            'content' => view('admin/dashboard', [])
        ]);
    }
}
