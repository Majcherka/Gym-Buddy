<?php

use db\Database;

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once 'Database.php';

if (!isset($_GET['category'])) {
    echo json_encode(['error' => 'Missing category']);
    exit;
}

$category = $_GET['category'];

try {
    $db = Database::getInstance()->getConnection();

    // Pobierz użytkownika
    $stmt = $db->prepare("
    SELECT 
    u.id, 
    u.name, 
    u.email, 
    u.age, 
    d.description,

    (
        SELECT string_agg(tc.name, ',' ORDER BY tc.id)
        FROM user_training_categories utc
        JOIN training_categories tc ON utc.category_id = tc.id
        WHERE utc.user_id = u.id
    ) AS categories,

    (
        SELECT string_agg(name, ',') FROM (
            SELECT dy.name AS name
            FROM user_days ud
            JOIN days dy ON ud.day_id = dy.id
            WHERE ud.user_id = u.id
            ORDER BY day_id
        )
    ) AS availability,

    (
        SELECT string_agg(l.name, ',' ORDER BY l.name)
        FROM user_locations ul
        JOIN locations l ON ul.location_id = l.id
        WHERE ul.user_id = u.id
    ) AS locations

FROM users u
LEFT JOIN descriptions d ON u.id = d.user_id
WHERE EXISTS (
    SELECT 1 FROM user_training_categories utc
    JOIN training_categories tc ON utc.category_id = tc.id
    WHERE utc.user_id = u.id AND tc.name = :category
)
ORDER BY random()
LIMIT 1;
    ");
    $stmt->execute(['category' => $category]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Pobierz kategorie przypisane temu użytkownikowi
        $catStmt = $db->prepare("
            SELECT tc.name
            FROM training_categories tc
            JOIN user_training_categories utc ON tc.id = utc.category_id
            WHERE utc.user_id = :user_id
        ");
        $catStmt->execute(['user_id' => $user['id']]);
        $categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

        $user['categories'] = $categories;

        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No users found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
