<?php

use db\Database;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'Database.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['categories'], $data['days'], $data['city'], $data['user_id'], $data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    $db = Database::getInstance()->getConnection();

    $userId = $data['user_id'];

    // 🧹 Usuń stare dane
    $db->prepare("DELETE FROM user_training_categories WHERE user_id = :user_id")->execute(['user_id' => $userId]);
    $db->prepare("DELETE FROM user_days WHERE user_id = :user_id")->execute(['user_id' => $userId]);

    // ➕ Zapisz nowe kategorie
    foreach ($data['categories'] as $categoryName) {
        $stmt = $db->prepare("SELECT id FROM training_categories WHERE name = :name");
        $stmt->execute(['name' => $categoryName]);
        $cat = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cat) {
            $db->prepare("INSERT INTO user_training_categories (user_id, category_id) VALUES (:user_id, :category_id)")
                ->execute(['user_id' => $userId, 'category_id' => $cat['id']]);
        }
    }

    // ➕ Zapisz dni
    foreach ($data['days'] as $dayName) {
        $stmt = $db->prepare("SELECT id FROM days WHERE name = :name");
        $stmt->execute(['name' => $dayName]);
        $day = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($day) {
            $db->prepare("INSERT INTO user_days (user_id, day_id) VALUES (:user_id, :day_id)")
                ->execute(['user_id' => $userId, 'day_id' => $day['id']]);
        }
    }

    // ➕ Lokalizacja
    $stmt = $db->prepare("SELECT id FROM locations WHERE name = :name");
    $stmt->execute(['name' => $data['city']]);
    $loc = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($loc) {
        // Najpierw usuń starą lokalizację
        $db->prepare("DELETE FROM user_locations WHERE user_id = :user_id")
            ->execute(['user_id' => $userId]);

        // Potem dodaj nową
        $db->prepare("INSERT INTO user_locations (user_id, location_id) VALUES (:user_id, :location_id)")
            ->execute(['user_id' => $userId, 'location_id' => $loc['id']]);
    }

    // ➕ Zapisz opis
    $db->prepare("DELETE FROM descriptions WHERE user_id = :user_id")
        ->execute(['user_id' => $userId]);

    $db->prepare("INSERT INTO descriptions (user_id, description) VALUES (:user_id, :description)")
        ->execute([
            'user_id' => $userId,
            'description' => $data['description']
        ]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
