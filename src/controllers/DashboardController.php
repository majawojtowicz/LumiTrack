<?php

require_once "AppController.php";

class DashboardController extends AppController
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        return $this->render('dashboard', [
            'user' => $_SESSION['user']
        ]);
    }
}
