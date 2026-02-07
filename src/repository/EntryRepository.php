<?php

require_once 'Repository.php';

class EntryRepository extends Repository
{
    protected $database;

    public function __construct()
    {
        $this->database = new Database();
    }
    public function getAllEntriesForAdmin(): array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM v_admin_activity_log 
            ORDER BY created_at DESC 
            LIMIT 10
        ');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEntry($userId, $energy, $mood, $focus, $note, $tags = [])
    
{
    $db = $this->database->connect();
    $db->beginTransaction();
    try {
        $stmt = $db->prepare('INSERT INTO entries (user_id, energy, mood, focus, note) VALUES (?,?,?,?,?) RETURNING id');
        $stmt->execute([$userId, $energy, $mood, $focus, $note]);
        $entryId = $stmt->fetchColumn();

        foreach ($tags as $tagName) {
            
            $stmtTag = $db->prepare('INSERT INTO tags (name) VALUES (?) ON CONFLICT (name) DO UPDATE SET name=EXCLUDED.name RETURNING id');
            $stmtTag->execute([$tagName]);
            $tagId = $stmtTag->fetchColumn();

            
            $stmtMap = $db->prepare('INSERT INTO entry_tags (entry_id, tag_id) VALUES (?, ?)');
            $stmtMap->execute([$entryId, $tagId]);
        }
        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
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

public function getEntriesWithTags(int $userId) {
    $stmt = $this->database->connect()->prepare('
        SELECT e.*, STRING_AGG(t.name, \', \') as tags_list
        FROM entries e
        LEFT JOIN entry_tags et ON e.id = et.entry_id
        LEFT JOIN tags t ON et.tag_id = t.id
        WHERE e.user_id = ?
        GROUP BY e.id ORDER BY e.created_at DESC
    ');
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getAdminActivityLog() {
    $stmt = $this->database->connect()->prepare('
        SELECT * FROM v_admin_activity_log 
        ORDER BY created_at DESC 
        LIMIT 10
    ');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
