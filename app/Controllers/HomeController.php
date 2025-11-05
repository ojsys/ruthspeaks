<?php
declare(strict_types=1);

namespace App\Controllers;

use function App\view;
use App\Models\Post;
use App\Models\Category;

class HomeController {
    public static function index(): void {
        $catId = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
        $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
        $posts = Post::latest(['limit'=>12, 'category_id'=>$catId, 'q'=>$q]);
        $categories = Category::all();
        $content = view('home', [ 'posts' => $posts, 'categories' => $categories, 'selectedCat'=>$catId, 'q'=>$q ]);
        echo view('layout', [
            'title' => 'RuthSpeaksTruth — Deep, delightful, and real faith',
            'content' => $content,
            'meta' => [
                'description' => 'Faith, growth, womanhood, family — honest posts with grace and laughter.'
            ]
        ]);
    }
}
