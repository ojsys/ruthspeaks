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

        // Get dashboard stats
        $pdo = \App\db();

        $postsCount = (int)$pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn();
        $publishedCount = (int)$pdo->query('SELECT COUNT(*) FROM posts WHERE published_at IS NOT NULL')->fetchColumn();
        $draftsCount = $postsCount - $publishedCount;
        $categoriesCount = (int)$pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
        $tagsCount = (int)$pdo->query('SELECT COUNT(*) FROM tags')->fetchColumn();
        $giveawaysCount = (int)$pdo->query('SELECT COUNT(*) FROM giveaways')->fetchColumn();

        // Recent posts
        $recentPosts = $pdo->query('SELECT id, title, slug, published_at, created_at FROM posts ORDER BY created_at DESC LIMIT 5')->fetchAll();

        echo \App\admin_view('dashboard', [
            'title' => 'Dashboard',
            'stats' => [
                'posts' => $postsCount,
                'published' => $publishedCount,
                'drafts' => $draftsCount,
                'categories' => $categoriesCount,
                'tags' => $tagsCount,
                'giveaways' => $giveawaysCount
            ],
            'recentPosts' => $recentPosts
        ]);
    }
}
