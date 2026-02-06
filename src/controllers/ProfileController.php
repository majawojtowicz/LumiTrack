<?php

require_once 'AppController.php';

class ProfileController extends AppController
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        return $this->render('profile', [
            'user' => $_SESSION['user'],
            'page' => 'profile'
        ]);
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /login');
    }
}