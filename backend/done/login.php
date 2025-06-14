<?php

use db\Database;

header("Content-Type: application/json");
require_once 'Database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email'], $data['password'])) {
    echo json_encode(['error' => 'Missing email or password']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT id, password_hash, role FROM users WHERE email = :email");
    $stmt->execute(['email' => $data['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($data['password'], $user['password_hash'])) {
        echo json_encode([
            'success' => true,
            'user_id' => $user['id'],
            'role' => $user['role']
        ]);
    } else {
        echo json_encode(['error' => 'Invalid credentials']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Server error']);
}