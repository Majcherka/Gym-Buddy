<?php

use db\Database;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once 'Database.php';

$data = json_decode(file_get_contents('php://input'), true);

$invitationId = $data['invitation_id'] ?? null;
$status = $data['status'] ?? null;

if (!$invitationId || !in_array($status, ['accepted', 'rejected'])) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("UPDATE invitations SET status = :status WHERE id = :id");
$stmt->execute(['status' => $status, 'id' => $invitationId]);

echo json_encode(['success' => true]);
