<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Settings;
use function App\admin_view;
use function App\verify_csrf;
use function App\handleUploadFromForm;

class AdminSettingsController {
    private static function guard(): void {
        if (!isset($_SESSION['admin'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    public static function index(): void {
        self::guard();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            verify_csrf();

            // Handle image uploads
            $siteLogo = handleUploadFromForm('site_logo') ?: (trim($_POST['site_logo_url'] ?? '') ?: Settings::get('site_logo'));
            $siteFavicon = handleUploadFromForm('site_favicon') ?: (trim($_POST['site_favicon_url'] ?? '') ?: Settings::get('site_favicon'));
            $heroImage = handleUploadFromForm('hero_image') ?: (trim($_POST['hero_image_url'] ?? '') ?: Settings::get('hero_image'));
            $aboutImage = handleUploadFromForm('about_page_image') ?: (trim($_POST['about_page_image_url'] ?? '') ?: Settings::get('about_page_image'));

            // Save all settings
            Settings::set('site_logo', $siteLogo, 'image');
            Settings::set('site_favicon', $siteFavicon, 'image');
            Settings::set('hero_image', $heroImage, 'image');
            Settings::set('about_page_image', $aboutImage, 'image');
            Settings::set('site_tagline', trim($_POST['site_tagline'] ?? ''), 'textarea');
            Settings::set('footer_text', trim($_POST['footer_text'] ?? ''), 'text');
            Settings::set('contact_email', trim($_POST['contact_email'] ?? ''), 'text');
            Settings::set('social_instagram', trim($_POST['social_instagram'] ?? ''), 'text');
            Settings::set('social_twitter', trim($_POST['social_twitter'] ?? ''), 'text');
            Settings::set('social_facebook', trim($_POST['social_facebook'] ?? ''), 'text');

            header('Location: /admin/settings?saved=1');
            exit;
        }

        $settings = Settings::getAllAsArray();
        $saved = isset($_GET['saved']);

        echo admin_view('settings', [
            'title' => 'Site Settings',
            'settings' => $settings,
            'saved' => $saved
        ]);
    }
}
