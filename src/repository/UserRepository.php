<?php

require_once "Repository.php";
class UserRepository extends Repository{
    protected $database;

    public function getUsers(): ?array
    {
        $query = $this->database->connect()->prepare("SELECT * FROM users");
        $query->execute();
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    public function addUser(string $username, string $password): void
    {
        $query = $this->database->connect()->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $query->execute([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);
    }

    public function getUserByEmail(string $email) {
        $query = $this->database->connect()->prepare('
            SELECT * FROM users WHERE email = :email
        ');
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $users = $query->fetch(PDO::FETCH_ASSOC);
       return $users;
    }

    public function createUser(string $firstname, string $email, string $hashedpassword, string $lastname, string $bio=''): void {
        $query = $this->database->connect()->prepare('
            INSERT INTO users (firstname, email, hashedpassword, lastname, bio) 
            VALUES (?, ?, ?, ?, ?)
        ');


        $query->execute([$firstname, $email, $hashedpassword, $lastname, $bio]);

    }
    
}