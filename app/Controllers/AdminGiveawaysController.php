<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Giveaway; use App\Models\Post; use App\Database;
use function Appiew;
use function App\admin_view; use function App\verify_csrf;

class AdminGiveawaysController {
    private static function guard(): void { if (!isset($_SESSION['admin'])) { header('Location: /admin/login'); exit; } }

    public static function index(): void {
        self::guard();
        $pdo = Database::pdo();
        $rows = $pdo->query("SELECT g.*, p.title as post_title FROM giveaways g JOIN posts p ON p.id=g.post_id ORDER BY g.created_at DESC")->fetchAll();
        echo admin_view('giveaways_list', array_merge(['title' => 'Giveaways'], ['items'=>$rows]));
    }

    public static function create(): void {
        self::guard();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            verify_csrf();
            $pdo = Database::pdo();
            $stmt = $pdo->prepare("INSERT INTO giveaways (post_id, title, start_at, end_at, max_winners, rules_json) VALUES (:pid,:title,:start,:end,:max,:rules)");
            $rules = [];
            if (!empty($_POST['keyword'])) $rules['keyword'] = trim($_POST['keyword']);
            $stmt->execute([
                ':pid'=>(int)$_POST['post_id'], ':title'=>trim($_POST['title']),
                ':start'=>($_POST['start_at'] ?: null), ':end'=>($_POST['end_at'] ?: null),
                ':max'=>(int)($_POST['max_winners'] ?? 0), ':rules'=> json_encode($rules)
            ]);
            header('Location: /admin/giveaways'); exit;
        }
        echo admin_view('giveaways_form', array_merge(['title' => 'New Giveaway'], ['giveaway'=>null, 'posts'=>Post::all(500)]));
    }

    public static function edit(int $id): void {
        self::guard();
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM giveaways WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        $g = $stmt->fetch();
        if (!$g) { header('Location: /admin/giveaways'); exit; }
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            verify_csrf();
            $stmt = $pdo->prepare("UPDATE giveaways SET post_id=:pid,title=:title,start_at=:start,end_at=:end,max_winners=:max,rules_json=:rules WHERE id=:id");
            $rules = [];
            if (!empty($_POST['keyword'])) $rules['keyword'] = trim($_POST['keyword']);
            $stmt->execute([
                ':pid'=>(int)$_POST['post_id'], ':title'=>trim($_POST['title']), ':start'=>($_POST['start_at'] ?: null), ':end'=>($_POST['end_at'] ?: null), ':max'=>(int)($_POST['max_winners'] ?? 0), ':rules'=> json_encode($rules), ':id'=>$id
            ]);
            header('Location: /admin/giveaways'); exit;
        }
        echo admin_view('giveaways_form', array_merge(['title' => 'Edit Giveaway'], ['giveaway'=>$g, 'posts'=>Post::all(500)]));
    }

    public static function delete(int $id): void {
        self::guard();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            verify_csrf();
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('DELETE FROM giveaways WHERE id=:id');
            $stmt->execute([':id'=>$id]);
            header('Location: /admin/giveaways'); exit;
        }
        echo admin_view('confirm_delete', array_merge(['title' => 'Delete Giveaway'], [
                'resource' => 'giveaway',
                'name' => '#'.$id,
                'action' => '/admin/giveaways/'.$id.'/delete'
            ]));
    }
}

