<?php

require_once "Repository.php";

class UserRepository extends Repository {

    public function getEntryCount(int $userId): int 
    {
        $stmt = $this->database->connect()->prepare('SELECT count_user_entries(?)');
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

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

    public function registerUserWithProfile($email, $password, $firstname, $lastname) {
        $db = $this->database->connect();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare('
                INSERT INTO users (email, password, firstname, lastname, role) 
                VALUES (?, ?, ?, ?, ?) RETURNING id
            ');
            $stmt->execute([$email, $password, $firstname, $lastname, 'USER']);
            $userId = $stmt->fetchColumn();

            $stmt2 = $db->prepare('
                INSERT INTO user_profiles (user_id, display_name) 
                VALUES (?, ?)
            ');
            $stmt2->execute([$userId, $firstname]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
}

public function getUserProfile(int $userId) {
    $stmt = $this->database->connect()->prepare('SELECT * FROM user_profiles WHERE user_id = ?');
    $stmt->execute([$userId]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    return $profile ? $profile : null;
}

public function updateProfile(int $userId, string $displayName, string $bio) {
    $stmt = $this->database->connect()->prepare('
        INSERT INTO user_profiles (user_id, display_name, bio) 
        VALUES (?, ?, ?) 
        ON CONFLICT (user_id) DO UPDATE SET display_name = EXCLUDED.display_name, bio = EXCLUDED.bio
    ');
    $stmt->execute([$userId, $displayName, $bio]);
}
}