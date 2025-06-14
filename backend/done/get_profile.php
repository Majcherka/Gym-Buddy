<?php

use db\Database;

header("Access-Control-Allow-Origin: http://localhost:63342");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

require_once 'Database.php';

if (!isset($_GET['user_id'])) {
    echo json_encode(['error' => 'Missing user_id']);
    exit;
}

$userId = $_GET['user_id'];

try {
    $db = Database::getInstance()->getConnection();

    // Get user basic info (name and age)
    $stmt = $db->prepare("SELECT name, age FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    // Get exercise categories
    $stmt = $db->prepare("SELECT tc.name FROM user_training_categories utc
        JOIN training_categories tc ON utc.category_id = tc.id
        WHERE utc.user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Get days
    $stmt = $db->prepare("SELECT d.name FROM user_days ud
        JOIN days d ON ud.day_id = d.id
        WHERE ud.user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $days = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Get location
    $stmt = $db->prepare("SELECT l.name FROM user_locations ul
        JOIN locations l ON ul.location_id = l.id
        WHERE ul.user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $location = $stmt->fetchColumn();

    // Get description
    $stmt = $db->prepare("SELECT description FROM descriptions WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $description = $stmt->fetchColumn();

    echo json_encode([
        'name' => $user['name'],
        'age' => $user['age'],
        'categories' => $categories,
        'days' => $days,
        'city' => $location,
        'description' => $description
    ]);

} catch (Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
