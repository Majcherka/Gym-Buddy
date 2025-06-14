<?php

use db\Database;

require_once __DIR__ . '/../db/Database.php';

class MatchService
{
    private PDO $db;
    private const ACTION_LIKE = 'like';
    private const ACTION_DISLIKE = 'dislike';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function recordAction(int $userId, int $targetId, string $action): bool
    {
        $table = match ($action) {
            self::ACTION_LIKE => 'liked_users',
            self::ACTION_DISLIKE => 'disliked_users',
            default => null
        };

        if (!$table) {
            return false;
        }

        $sql = "INSERT INTO $table (user_id, " . ($action === self::ACTION_LIKE ? 'liked_user_id' : 'disliked_user_id') . ")
                VALUES (:user_id, :target_id)
                ON CONFLICT DO NOTHING"; // PostgreSQL version of IGNORE

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'user_id' => $userId,
            'target_id' => $targetId
        ]);
    }
}