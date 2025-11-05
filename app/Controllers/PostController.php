<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Post;
use App\Models\Giveaway;
use function App\view;
use function App\notFound;
use function App\estimateMinTimeMs;

class PostController {
    public static function show(string $slug): void {
        $post = Post::findBySlug($slug);
        if (!$post) { notFound(); return; }
        $giveaway = Giveaway::activeForPost((int)$post['id']);
        $minTimeMs = estimateMinTimeMs((int)($post['estimated_read_minutes'] ?? 3));
        $content = view('post', [ 'post' => $post, 'giveaway' => $giveaway, 'minTimeMs' => $minTimeMs ]);
        echo view('layout', [
            'title' => $post['title'] . ' — RuthSpeaksTruth',
            'content' => $content,
            'meta' => [
                'description' => $post['excerpt'] ?? '',
                'image' => $post['cover_image'] ?? ''
            ]
        ]);
    }
}

