<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Post;
use App as AppNs; // for constants

class FeedController {
    public static function rss(): void {
        $posts = Post::recent(20);
        header('Content-Type: application/rss+xml; charset=UTF-8');
        $base = \App\SITE_BASE_URL ?: '';
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<rss version=\"2.0\"><channel>";
        echo '<title>' . \App\e(\App\SITE_NAME) . '</title>';
        echo '<link>' . \App\e($base ?: '/') . '</link>';
        echo '<description>Recent posts from ' . \App\e(\App\SITE_NAME) . '</description>';
        foreach ($posts as $p) {
            $link = $base . '/post/' . $p['slug'];
            echo '<item>';
            echo '<title>' . \App\e($p['title']) . '</title>';
            echo '<link>' . \App\e($link) . '</link>';
            echo '<guid>' . \App\e($link) . '</guid>';
            echo '<pubDate>' . date(DATE_RSS, strtotime($p['published_at'])) . '</pubDate>';
            echo '<description>' . \App\e($p['excerpt'] ?? '') . '</description>';
            echo '</item>';
        }
        echo '</channel></rss>';
    }

    public static function sitemap(): void {
        $posts = Post::recent(200);
        header('Content-Type: application/xml; charset=UTF-8');
        $base = \App\SITE_BASE_URL ?: '';
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        // Home
        echo '<url><loc>' . \App\e($base ?: '/') . '</loc></url>';
        foreach ($posts as $p) {
            $link = $base . '/post/' . $p['slug'];
            echo '<url><loc>' . \App\e($link) . '</loc>';
            if (!empty($p['published_at'])) echo '<lastmod>' . date('c', strtotime($p['published_at'])) . '</lastmod>';
            echo '</url>';
        }
        echo '</urlset>';
    }
}

