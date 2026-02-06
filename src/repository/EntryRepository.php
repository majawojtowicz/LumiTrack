<?php

require_once 'Repository.php';

class EntryRepository extends Repository
{
    protected $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function createEntry($userId, $energy, $mood, $focus, $note)
{
    $stmt = $this->database->connect()->prepare(
        'INSERT INTO entries (user_id, energy, mood, focus, note)
         VALUES (?,?,?,?,?)'
    );

    $stmt->execute([
            $userId,
            $energy,
            $mood,
            $focus,
            $note
        ]);
}

    public function findByUser(int $userId): array
    {
        $stmt = $this->database->connect()->prepare(
            'SELECT *
             FROM entries
             WHERE user_id = ?
             ORDER BY created_at DESC'
        );

        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteEntry(int $id, int $userId)
{
    $stmt = $this->database->connect()->prepare(
        'DELETE FROM entries WHERE id = ? AND user_id = ?'
    );
    $stmt->execute([$id, $userId]);
}
}
