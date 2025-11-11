<?php
declare(strict_types=1);

namespace App\Controllers;

use function App\serverJson; use function App\handleUploadFromForm;

class UploadController {
    public static function upload(): void {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') { serverJson(['ok'=>false],405); return; }
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            serverJson(['ok'=>false,'message'=>'Unauthorized'],403);
            return;
        }
        $url = handleUploadFromForm('file');
        if ($url) serverJson(['ok'=>true,'url'=>$url]);
        else serverJson(['ok'=>false,'message'=>'Upload failed'],400);
    }
}

