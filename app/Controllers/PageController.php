<?php
declare(strict_types=1);

namespace App\Controllers;

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
}

