<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';

class AdminController extends AppController
{
    public function index()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        $userRepository = new UserRepository();
        $users = $userRepository->getUsers();

        return $this->render('admin', [
            'users' => $users,
            'user' => $_SESSION['user']
        ]);
    }

    public function toggleBlock()
    
    {       $this->requireAdmin();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            header('Content-type: application/json');
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $userRepository = new UserRepository();
            $newStatus = $userRepository->toggleUserBlock((int)$id);
            
            header('Content-type: application/json');
            echo json_encode(['success' => true, 'is_blocked' => $newStatus]);
        } else {
            http_response_code(400);
        }
    }
}