<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Page;
use function App\view;

class PageController {
    public static function about(): void {
        $content = view('about', []);
        echo view('layout', [
            'title' => 'About — RuthSpeaksTruth',
            'content' => $content,
            'meta' => [ 'description' => 'About Ruth Goodness Onah and RuthSpeaksTruth.' ]
        ]);
    }

    public static function show(string $slug): void {
        $page = Page::findBySlug($slug);

        if (!$page) {
            http_response_code(404);
            echo view('layout', [
                'title' => 'Page Not Found',
                'content' => '<div class="container"><div class="content"><h1>Page Not Found</h1><p>The page you requested does not exist.</p></div></div>'
            ]);
            return;
        }

        $content = view('page', ['page' => $page]);
        echo view('layout', [
            'title' => $page['title'] . ' — RuthSpeaksTruth',
            'content' => $content,
            'meta' => [
                'description' => $page['excerpt'] ?? substr(strip_tags($page['content']), 0, 160),
                'image' => $page['featured_image'] ?? ''
            ]
        ]);
    }
}

