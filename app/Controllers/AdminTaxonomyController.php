<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Category; use App\Models\Tag;
use function Appiew;
use function App\admin_view; use function App\slugify; use function App\verify_csrf;

class AdminTaxonomyController {
    private static function guard(): void { if (!isset($_SESSION['admin'])) { header('Location: /admin/login'); exit; } }

    public static function categories(): void {
        self::guard();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method === 'POST') {
            verify_csrf();
            if (isset($_POST['create'])) {
                $name = trim($_POST['name'] ?? '');
                $slug = trim($_POST['slug'] ?? '') ?: slugify($name);
                Category::create($name, $slug);
            } elseif (isset($_POST['update'])) {
                Category::update((int)$_POST['id'], trim($_POST['name']), trim($_POST['slug']));
            } elseif (isset($_POST['delete'])) {
                Category::delete((int)$_POST['id']);
            }
            header('Location: /admin/categories'); exit;
        }
        echo admin_view('categories', array_merge(['title' => 'Categories'], ['items'=>Category::all(), 'type'=>'category']));
    }

    public static function tags(): void {
        self::guard();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method === 'POST') {
            verify_csrf();
            if (isset($_POST['create'])) {
                $name = trim($_POST['name'] ?? '');
                $slug = trim($_POST['slug'] ?? '') ?: slugify($name);
                Tag::create($name, $slug);
            } elseif (isset($_POST['update'])) {
                Tag::update((int)$_POST['id'], trim($_POST['name']), trim($_POST['slug']));
            } elseif (isset($_POST['delete'])) {
                Tag::delete((int)$_POST['id']);
            }
            header('Location: /admin/tags'); exit;
        }
        echo admin_view('categories', array_merge(['title' => 'Tags'], ['items'=>Tag::all(), 'type'=>'tag']));
    }
}

