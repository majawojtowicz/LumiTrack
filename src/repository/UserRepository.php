<?php

require_once "Repository.php";

class UserRepository extends Repository {

    public function getUsers(): ?array
    {
        $query = $this->database->connect()->prepare("SELECT * FROM users ORDER BY id DESC");
        $query->execute();
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    public function getUserByEmail(string $email) {
        $query = $this->database->connect()->prepare('
            SELECT * FROM users WHERE email = :email
        ');
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function createUser(string $firstname, string $email, string $password, string $lastname): void {
        $query = $this->database->connect()->prepare('
            INSERT INTO users (firstname, email, password, lastname, role, is_blocked) 
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        $query->execute([$firstname, $email, $password, $lastname, 'USER', 0]);
    }

    public function toggleUserBlock(int $id): bool {
        $db = $this->database->connect();
        
        $stmt = $db->prepare('SELECT is_blocked FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $currentStatus = $stmt->fetchColumn();

        $newStatus = $currentStatus ? 0 : 1;

        $query = $db->prepare('UPDATE users SET is_blocked = :status WHERE id = :id');
        $query->bindParam(':status', $newStatus, PDO::PARAM_INT);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        return (bool)$newStatus;
    }
}