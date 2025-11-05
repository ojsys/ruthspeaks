<?php
declare(strict_types=1);

namespace App\Controllers;

use function App\render;
use function App\view;
use App\Models\Post;

class HomeController {
    public static function index(): void {
        $posts = Post::recent(12);
        $content = view('home', [ 'posts' => $posts ]);
        echo view('layout', [
            'title' => 'RuthSpeaksTruth — Deep, delightful, and real faith',
            'content' => $content,
            'meta' => [
                'description' => 'Faith, growth, womanhood, family — honest posts with grace and laughter.'
            ]
        ]);
    }
}

