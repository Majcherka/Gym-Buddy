<?php

use db\Database;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once 'Database.php';

$userId = $_GET['user_id'] ?? null;
$status = $_GET['status'] ?? 'pending'; // <-- NOWE

if (!$userId) {
    echo json_encode([]);
    exit;
}


// Zabezpieczenie przed nieprawidłowym statusem
$allowedStatuses = ['pending', 'accepted', 'rejected'];
if (!in_array($status, $allowedStatuses)) {
    echo json_encode(['error' => 'Invalid status']);
    exit;
}

$db = Database::getInstance()->getConnection();$stmt = $db->prepare("
    SELECT 
        i.id AS invitation_id, 
        i.sender_id, 
        u.name AS sender_name, 
        u.age AS sender_age,
        i.message, 
        g.name AS gym
    FROM invitations i
    JOIN users u ON i.sender_id = u.id
    JOIN gyms g ON i.gym_id = g.id
    WHERE i.receiver_id = :user_id AND i.status = :status
    ORDER BY i.id DESC
");

$stmt->execute([
    'user_id' => $userId,
    'status' => $status
]);$invitations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Doładuj dodatkowe dane
foreach ($invitations as &$inv) {
    $senderId = $inv['invitation_id'];

    $catStmt = $db->prepare("SELECT tc.name FROM user_training_categories utc JOIN training_categories tc ON utc.category_id = tc.id WHERE utc.user_id = :uid");
    $catStmt->execute(['uid' => $inv['sender_id']]);
    $inv['categories'] = $catStmt->fetchAll(PDO::FETCH_COLUMN);

    $daysStmt = $db->prepare("SELECT d.name FROM user_days ud JOIN days d ON ud.day_id = d.id WHERE ud.user_id = :uid");
    $daysStmt->execute(['uid' => $inv['sender_id']]);
    $inv['days'] = $daysStmt->fetchAll(PDO::FETCH_COLUMN);
}

echo json_encode($invitations);
