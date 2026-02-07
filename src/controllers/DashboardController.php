<?php

require_once "AppController.php";
require_once __DIR__.'/../repository/EntryRepository.php';

class DashboardController extends AppController
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $this->renderError(401);
            header("Location: /login");
            exit;
        }

        return $this->render('dashboard', [
            'user' => $_SESSION['user']
        ]);
    }

    public function saveEntry()
{


    if (!$this->isPost()) {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }


    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Not authenticated']);
        return;
    }

    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid content type']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        $this->renderError(400);
    }

    $userId = $_SESSION['user']['id'];
    $energy = $data['energy'] ?? null;
    $mood   = $data['mood'] ?? null;
    $focus  = $data['focus'] ?? null;
    $note   = $data['note'] ?? null;
    $tags   = $data['tags'] ?? [];

    if (!$energy || !$mood || !$focus) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing fields']);
        return;
    }

    $repo = new EntryRepository();

    $repo->createEntry(
        $userId,
        $energy,
        $mood,
        $focus,
        $note,
        $tags
    );

    http_response_code(200);
    echo json_encode(['success' => true]);
}
}