<?php

use db\Database;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once 'Database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['sender_id'], $data['receiver_id'], $data['gym_id'], $data['message'])) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();

    $stmt = $db->prepare("
        INSERT INTO invitations (sender_id, receiver_id, gym_id, message, status, created_at)
        VALUES (:sender_id, :receiver_id, :gym_id, :message, :status, NOW())
    ");

    $status = 'pending';  // <== domyślny status
    $stmt->bindParam(':sender_id', $data['sender_id'], PDO::PARAM_INT);
    $stmt->bindParam(':receiver_id', $data['receiver_id'], PDO::PARAM_INT);
    $stmt->bindParam(':gym_id', $data['gym_id'], PDO::PARAM_INT);
    $stmt->bindParam(':message', $data['message'], PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);

    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
