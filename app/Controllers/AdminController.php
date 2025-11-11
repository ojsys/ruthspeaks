<?php
declare(strict_types=1);

namespace App\Controllers;

use function App\view;
use function App\e;

class AdminController {
    public static function login(): void {
        // Redirect to main login page
        header('Location: /login');
        exit;
    }

    public static function logout(): void {
        unset($_SESSION['user']);
        unset($_SESSION['admin']);
        session_destroy();
        header('Location: /login');
        exit;
    }

    public static function dashboard(): void {
        \App\requireAdmin();

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
