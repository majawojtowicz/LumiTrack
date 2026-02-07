<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';

class ProfileController extends AppController
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $this->renderError(401);
            header('Location: /login');
            exit;
        }
        $user = $_SESSION['user'];
        $userId = $user['id'];

        $userRepository = new UserRepository();
        $profile = $userRepository->getUserProfile($userId);
        $count = $userRepository->getEntryCount($userId);


        return $this->render('profile', [
            'user' => $user,
            'page' => 'profile',
            'profile' => $profile,
            'entryCount' => $count
        ]);
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /login');
    }

    public function updateProfile()
    {
        if (!$this->isPost()) {
            header('Location: /profile');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $displayName = $_POST['display_name'];
        $bio = $_POST['bio'];

        $userRepository = new UserRepository();
        $userRepository->updateProfile($userId, $displayName, $bio);

        header('Location: /profile?success=1');
    }
}