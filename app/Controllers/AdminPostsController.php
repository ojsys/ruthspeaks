<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use function App\view;
use function App\admin_view;
use function App\e;
use function App\slugify;
use function App\csrf_field;
use function App\verify_csrf;
use function App\handleUploadFromForm;

class AdminPostsController {
    private static function guard(): void {
        if (!isset($_SESSION['admin'])) { header('Location: /admin/login'); exit; }
    }

    public static function index(): void {
        self::guard();
        $posts = Post::all(200);
        echo admin_view('posts_list', [
            'title' => 'Posts',
            'posts' => $posts
        ]);
    }

    public static function create(): void {
        self::guard();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            verify_csrf();
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
            $excerpt = trim($_POST['excerpt'] ?? '');
            $content = (string)($_POST['content'] ?? '');
            $erm = max(1, (int)($_POST['estimated_read_minutes'] ?? 4));
            $cat = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
            $cover = handleUploadFromForm('cover_image') ?: (trim($_POST['cover_image_url'] ?? '') ?: null);
            $pub = !empty($_POST['published_at']) ? $_POST['published_at'] : null;
            $id = Post::create([
                'slug'=>$slug,'title'=>$title,'excerpt'=>$excerpt,'content'=>$content,
                'cover_image'=>$cover,'category_id'=>$cat,'author_id'=>null,
                'estimated_read_minutes'=>$erm,'published_at'=>$pub
            ]);
            // Tags
            $tags = array_filter(array_map('trim', explode(',', (string)($_POST['tags'] ?? ''))));
            if ($id && $tags) { Post::setTags($id, $tags); }
            header('Location: /admin/posts'); exit;
        }
        $cats = Category::all();
        echo admin_view('posts_form', [
            'title' => 'New Post',
            'post' => null,
            'categories' => $cats
        ]);
    }

    public static function edit(int $id): void {
        self::guard();
        $post = Post::findById($id);
        if (!$post) { header('Location: /admin/posts'); exit; }
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            verify_csrf();
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
            $excerpt = trim($_POST['excerpt'] ?? '');
            $content = (string)($_POST['content'] ?? '');
            $erm = max(1, (int)($_POST['estimated_read_minutes'] ?? 4));
            $cat = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
            $cover = handleUploadFromForm('cover_image') ?: (trim($_POST['cover_image_url'] ?? '') ?: $post['cover_image']);
            $pub = !empty($_POST['published_at']) ? $_POST['published_at'] : null;
            Post::update($id, [
                'slug'=>$slug,'title'=>$title,'excerpt'=>$excerpt,'content'=>$content,
                'cover_image'=>$cover,'category_id'=>$cat,'author_id'=>$post['author_id'] ?? null,
                'estimated_read_minutes'=>$erm,'published_at'=>$pub
            ]);
            $tags = array_filter(array_map('trim', explode(',', (string)($_POST['tags'] ?? ''))));
            Post::setTags($id, $tags);
            header('Location: /admin/posts'); exit;
        }
        $cats = Category::all();
        // Preload tag string for UI
        $tagStr = implode(', ', Post::tagsFor($id));
        echo admin_view('posts_form', [
            'title' => 'Edit Post',
            'post' => $post,
            'categories' => $cats,
            'tags' => $tagStr
        ]);
    }

    public static function delete(int $id): void {
        self::guard();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            verify_csrf();
            Post::delete($id);
            header('Location: /admin/posts'); exit;
        }
        $post = Post::findById($id);
        echo admin_view('confirm_delete', [
            'title' => 'Delete Post',
            'resource' => 'post',
            'name' => $post['title'] ?? ('#'.$id),
            'action' => '/admin/posts/'.$id.'/delete'
        ]);
    }
}
