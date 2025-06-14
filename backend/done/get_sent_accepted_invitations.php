<?php

use db\Database;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once 'Database.php';

$userId = $_GET['user_id'] ?? null;

if (!$userId) {
    echo json_encode([]);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();

    $stmt = $db->prepare("
        SELECT 
            i.id AS invitation_id, 
            i.receiver_id, 
            u.name AS receiver_name, 
            u.age AS receiver_age,
            i.message, 
            g.name AS gym
        FROM invitations i
        JOIN users u ON i.receiver_id = u.id
        JOIN gyms g ON i.gym_id = g.id
        WHERE i.sender_id = :user_id AND i.status = 'accepted'
        ORDER BY i.id DESC
    ");
    $stmt->execute(['user_id' => $userId]);

    $invitations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($invitations as &$inv) {
        $receiverId = $inv['receiver_id'];

        // Kategorie treningowe
        $catStmt = $db->prepare("SELECT tc.name FROM user_training_categories utc JOIN training_categories tc ON utc.category_id = tc.id WHERE utc.user_id = :uid");
        $catStmt->execute(['uid' => $receiverId]);
        $inv['categories'] = $catStmt->fetchAll(PDO::FETCH_COLUMN);

        // Dostępność
        $daysStmt = $db->prepare("SELECT d.name FROM user_days ud JOIN days d ON ud.day_id = d.id WHERE ud.user_id = :uid ORDER BY d.id");
        $daysStmt->execute(['uid' => $receiverId]);
        $inv['days'] = $daysStmt->fetchAll(PDO::FETCH_COLUMN);
    }

    echo json_encode($invitations);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
