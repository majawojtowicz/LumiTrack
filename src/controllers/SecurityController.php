<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';

class SecurityController extends AppController {

    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login(){

        if(!$this->isPost()){
            return $this->render("login");
        }
        
        $username = $_POST["username"]??'';
        $password = $_POST["password"]??'';

        $user = $this->userRepository->getUserByEmail($username);


        if(!$user){
            return $this->render("login", ["messages" => ["Nie istnieje taki użytkownik!"]]);
        }

        if(!password_verify($password, $user['hashedpassword'])){
            return $this->render("login", ["messages" => ["Nieprawidłowy email lub hasło!"]]);
        }

        // TODO create user session, cookie itd.

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/dashboard");
    }

    public function register(){

        if(!$this->isGet()){
            return $this->render("register");
        }

        $username = $_POST["username"]??'';
        $password = $_POST["password"]??'';
        $firstname = $_POST["firstname"]??'';
        $lastname = $_POST["lastname"]??'';
        $password2 = $_POST["password2"]??'';


        if(empty($username) || empty($password) || empty($firstname) || empty($lastname) || empty($password2)) {
            return $this->render("register", ["messages" => ["Wypełnij wszystkie pola!"]]);
        }

        if ($password !== $password2) {
            return $this->render("register", ["messages" => ["Hasła nie są identyczne!"]]);
        }

        if ($this->userRepository->getUserByEmail($username) !== false) {
            return $this->render("register", ["messages" => ["Użytkownik o podanym emailu już istnieje!"]]);
        }

        $this->userRepository->createUser($firstname, $username, password_hash($password, PASSWORD_BCRYPT), $lastname);

        return $this->render("login",["messages"=>["Rejestracja przebiegła pomyślnie! Zaloguj się!"]]);
    }
}