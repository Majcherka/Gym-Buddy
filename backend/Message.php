<?php

use db\Database;

class Message {
public int $id;
public int $senderId;
public int $receiverId;
public string $content;
public string $timestamp;

public function save(): bool {
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, content, timestamp) VALUES (:sender, :receiver, :content, NOW())");
return $stmt->execute([
'sender' => $this->senderId,
'receiver' => $this->receiverId,
'content' => $this->content
]);
}

public static function getConversation(int $user1, int $user2): array {
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT * FROM messages WHERE (sender_id = :u1 AND receiver_id = :u2) OR (sender_id = :u2 AND receiver_id = :u1) ORDER BY timestamp ASC");
$stmt->execute(['u1' => $user1, 'u2' => $user2]);
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}