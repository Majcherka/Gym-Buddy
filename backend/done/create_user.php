<?php

use db\Database;

header('Content-Type: application/json');
require_once 'Database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['name'], $data['email'], $data['age'], $data['role'], $data['password'])) {
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("
        INSERT INTO users (name, email, password_hash, age, role)
        VALUES (:name, :email, :password, :age, :role)
    ");
    $stmt->execute([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        'age' => $data['age'],
        'role' => $data['role']
    ]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
