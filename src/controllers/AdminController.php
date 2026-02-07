<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../repository/EntryRepository.php';

class AdminController extends AppController
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
        $this->renderError(401);
    }

        if ($_SESSION['user']['role'] !== 'ADMIN') {
        $this->renderError(403);
    }

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        $userRepository = new UserRepository();
        $entryRepository = new EntryRepository();
        $users = $userRepository->getUsers();
        $logs = $entryRepository->getAllEntriesForAdmin();

        return $this->render('admin', [
            'users' => $users,
            'user' => $_SESSION['user'],
            'logs' => $logs
        ]);
    }

    public function toggleBlock()
    
    {      
        header('Content-type: application/json');
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            return $this->renderError(400);
        echo json_encode(['success' => false, 'message' => 'Brak ID użytkownika']);
        return;
    }

    try {
        $userRepository = new UserRepository();
        $newStatus = $userRepository->toggleUserBlock((int)$id);
        
        
        echo json_encode(['success' => true, 'is_blocked' => $newStatus]);
    } catch (Exception $e) {
        http_response_code(500);
        
        echo json_encode(['success' => false]);
    }
    }
}