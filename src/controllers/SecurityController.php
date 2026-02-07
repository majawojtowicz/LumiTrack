<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';

class SecurityController extends AppController {

    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login() {
        if (!$this->isPost()) {
            return $this->render("login");
        }
        
        $email = $_POST["username"] ?? '';
        $password = $_POST["password"] ?? '';

        if (empty($email) || empty($password)) {
            return $this->render("login", ["messages" => ["Proszę podać email i hasło!"]]);
        }

        $user = $this->userRepository->getUserByEmail($email);

        if (!$user) {
            return $this->render("login", ["messages" => ["Nie istnieje użytkownik o tym adresie email!"]]);
        }

        if (isset($user['is_blocked']) && $user['is_blocked'] == 1) {
            return $this->render("login", ["messages" => ["Twoje konto zostało zablokowane przez administratora!"]]);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->render("login", ["messages" => ["Nieprawidłowe hasło!"]]);
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'firstname' => $user['firstname'],
            'email' => $user['email'],
            'role' => $user['role'] ?? 'USER'
        ];

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/dashboard");
        exit;
    }

    public function register() {
        if (!$this->isPost()) {
            return $this->render("register");
        }

        $email = $_POST["username"] ?? ''; 
        $password = $_POST["password"] ?? '';
        $password2 = $_POST["password2"] ?? '';
        $firstname = $_POST["firstname"] ?? '';
        $lastname = $_POST["lastname"] ?? '';

        if (empty($email) || empty($password) || empty($firstname) || empty($lastname)) {
            return $this->render("register", ["messages" => ["Wszystkie pola są wymagane!"]]);
        }

        if ($password !== $password2) {
            return $this->render("register", ["messages" => ["Hasła nie są identyczne!"]]);
        }

        if ($this->userRepository->getUserByEmail($email)) {
            return $this->render("register", ["messages" => ["Ten adres email jest już zajęty!"]]);
        }

        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
            $this->userRepository->registerUserWithProfile(
                $email, 
                $hashedPassword, 
                $firstname, 
                $lastname
            );

            return $this->render("login", ["messages" => ["Rejestracja przebiegła pomyślnie! Teraz możesz się zalogować."]]);

        } catch (Exception $e) {
            error_log("Błąd rejestracji: " . $e->getMessage());
            return $this->renderError(500);
        }
    }
}