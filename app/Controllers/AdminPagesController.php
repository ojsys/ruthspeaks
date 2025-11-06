<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Page;
use function App\admin_view;
use function App\slugify;
use function App\verify_csrf;
use function App\handleUploadFromForm;

class AdminPagesController {
    private static function guard(): void {
        if (!isset($_SESSION['admin'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    public static function index(): void {
        self::guard();
        $pages = Page::all();
        echo admin_view('pages_list', [
            'title' => 'Pages',
            'pages' => $pages
        ]);
    }

    public static function create(): void {
        self::guard();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            verify_csrf();

            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
            $content = (string)($_POST['content'] ?? '');
            $excerpt = trim($_POST['excerpt'] ?? '');
            $featuredImage = handleUploadFromForm('featured_image') ?: (trim($_POST['featured_image_url'] ?? '') ?: null);
            $isPublished = isset($_POST['is_published']) ? 1 : 0;

            Page::create([
                'slug' => $slug,
                'title' => $title,
                'content' => $content,
                'excerpt' => $excerpt,
                'featured_image' => $featuredImage,
                'is_published' => $isPublished
            ]);

            header('Location: /admin/pages');
            exit;
        }

        echo admin_view('pages_form', [
            'title' => 'New Page',
            'page' => null
        ]);
    }

    public static function edit(int $id): void {
        self::guard();

        $page = Page::findById($id);
        if (!$page) {
            header('Location: /admin/pages');
            exit;
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            verify_csrf();

            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
            $content = (string)($_POST['content'] ?? '');
            $excerpt = trim($_POST['excerpt'] ?? '');
            $featuredImage = handleUploadFromForm('featured_image') ?: (trim($_POST['featured_image_url'] ?? '') ?: $page['featured_image']);
            $isPublished = isset($_POST['is_published']) ? 1 : 0;

            Page::update($id, [
                'slug' => $slug,
                'title' => $title,
                'content' => $content,
                'excerpt' => $excerpt,
                'featured_image' => $featuredImage,
                'is_published' => $isPublished
            ]);

            header('Location: /admin/pages');
            exit;
        }

        echo admin_view('pages_form', [
            'title' => 'Edit Page',
            'page' => $page
        ]);
    }

    public static function delete(int $id): void {
        self::guard();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            verify_csrf();
            Page::delete($id);
            header('Location: /admin/pages');
            exit;
        }

        $page = Page::findById($id);
        echo admin_view('confirm_delete', [
            'title' => 'Delete Page',
            'resource' => 'page',
            'name' => $page['title'] ?? ('#' . $id),
            'action' => '/admin/pages/' . $id . '/delete'
        ]);
    }
}
