<?php

use db\Database;

require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../models/Invitation.php';

class InvitationService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function send(array $data): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO invitations (sender_id, receiver_id, gym_id, message, status, created_at)
            VALUES (:sender_id, :receiver_id, :gym_id, :message, :status, NOW())
        ");

        $stmt->execute([
            'sender_id' => $data['sender_id'],
            'receiver_id' => $data['receiver_id'],
            'gym_id' => $data['gym_id'],
            'message' => $data['message'],
            'status' => 'pending'
        ]);
    }

    public function respond(int $invitationId, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE invitations SET status = :status WHERE id = :id");
        $stmt->execute([
            'status' => $status,
            'id' => $invitationId
        ]);
    }

    public function getReceived(int $userId, string $status): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                i.id AS invitation_id, 
                i.sender_id, 
                u.name AS sender_name, 
                u.age AS sender_age,
                i.message, 
                g.name AS gym,
                i.status,
                i.created_at
            FROM invitations i
            JOIN users u ON i.sender_id = u.id
            JOIN gyms g ON i.gym_id = g.id
            WHERE i.receiver_id = :user_id AND i.status = :status
            ORDER BY i.id DESC
        ");

        $stmt->execute([
            'user_id' => $userId,
            'status' => $status
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $invitations = array_map(fn($row) => Invitation::fromArray($row), $rows);

        foreach ($invitations as $inv) {
            $inv->categories = $this->fetchArray("
                SELECT tc.name FROM user_training_categories utc 
                JOIN training_categories tc ON utc.category_id = tc.id 
                WHERE utc.user_id = :uid", $inv->senderId);

            $inv->days = $this->fetchArray("
                SELECT d.name FROM user_days ud 
                JOIN days d ON ud.day_id = d.id 
                WHERE ud.user_id = :uid", $inv->senderId);
        }

        return array_map(fn($inv) => $inv->toArray(), $invitations);
    }

    public function getSentAccepted(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                i.id AS invitation_id, 
                i.receiver_id, 
                u.name AS receiver_name, 
                u.age AS receiver_age,
                i.message, 
                g.name AS gym,
                i.status,
                i.created_at
            FROM invitations i
            JOIN users u ON i.receiver_id = u.id
            JOIN gyms g ON i.gym_id = g.id
            WHERE i.sender_id = :user_id AND i.status = 'accepted'
            ORDER BY i.id DESC
        ");

        $stmt->execute(['user_id' => $userId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $invitations = array_map(fn($row) => Invitation::fromArray($row), $rows);

        foreach ($invitations as $inv) {
            $inv->categories = $this->fetchArray("
                SELECT tc.name FROM user_training_categories utc 
                JOIN training_categories tc ON utc.category_id = tc.id 
                WHERE utc.user_id = :uid", $inv->receiverId);

            $inv->days = $this->fetchArray("
                SELECT d.name FROM user_days ud 
                JOIN days d ON ud.day_id = d.id 
                WHERE ud.user_id = :uid ORDER BY d.id", $inv->receiverId);
        }

        return array_map(fn($inv) => $inv->toArray(), $invitations);
    }

    private function fetchArray(string $sql, int $uid): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $uid]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

