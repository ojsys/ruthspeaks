<?php
declare(strict_types=1);

namespace App\Controllers;

use function App\view;

class ContactController {
    public static function show(): void {
        $content = view('contact', []);
        echo view('layout', [
            'title' => 'Let\'s Talk — RuthSpeaksTruth',
            'content' => $content,
            'meta' => [
                'description' => 'Have a question, prayer request, or just want to chat? Let\'s connect!'
            ]
        ]);
    }

    public static function submit(): void {
        header('Content-Type: application/json');

        // Simple validation
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            echo json_encode(['ok' => false, 'message' => 'All fields are required']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['ok' => false, 'message' => 'Please enter a valid email']);
            exit;
        }

        // Log the contact form submission
        \App\Logger::info('Contact form submission', [
            'name' => $name,
            'email' => $email,
            'message' => substr($message, 0, 100)
        ]);

        // In a real app, you would send an email here
        // For now, we'll just log it and return success

        echo json_encode([
            'ok' => true,
            'message' => 'Thanks for reaching out! I\'ll get back to you soon.'
        ]);
    }
}
