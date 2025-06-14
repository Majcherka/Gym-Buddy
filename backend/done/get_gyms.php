<?php

use db\Database;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once 'Database.php';

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT id, name, city FROM gyms ORDER BY city, name");
    $gyms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($gyms);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
