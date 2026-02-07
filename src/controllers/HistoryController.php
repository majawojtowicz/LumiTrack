<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/EntryRepository.php';

class HistoryController extends AppController
{


    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $repo = new EntryRepository();
        $entries = $repo->getEntriesWithTags($_SESSION['user']['id']);

        return $this->render('history', [
            'entries' => $entries,
            'user' => $_SESSION['user']
        ]);
    }

    public function deleteEntry()
{
    $id = $_GET['id'] ?? null;
    if (!isset($_SESSION['user']) || !$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        return;
    }

    $repo = new EntryRepository();
    $repo->deleteEntry((int)$id, $_SESSION['user']['id']);

    http_response_code(200);
    echo json_encode(['success' => true]);
}
}