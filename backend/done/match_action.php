<?php

use db\Database;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once 'Database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id'], $data['target_id'], $data['action'])) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$userId = $data['user_id'];       // ZALOGOWANY użytkownik — CHOMIK
$targetId = $data['target_id'];   // WIDOCZNY profil — MICHAŁ
$action = $data['action'];

try {
    $db = Database::getInstance()->getConnection();

    if ($action === 'like') {
        $stmt = $db->prepare("INSERT IGNORE INTO liked_users (user_id, liked_user_id) VALUES (:user_id, :target_id)");
    } elseif ($action === 'dislike') {
        $stmt = $db->prepare("INSERT IGNORE INTO disliked_users (user_id, disliked_user_id) VALUES (:user_id, :target_id)");
    } else {
        echo json_encode(['error' => 'Invalid action']);
        exit;
    }

    $stmt->execute([
        'user_id' => $userId,
        'target_id' => $targetId
    ]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
