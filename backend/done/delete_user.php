<?php

use db\Database;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once 'Database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email'])) {
    echo json_encode(['error' => 'Missing email']);
    exit;
}

$email = $data['email'];

try {
    $db = Database::getInstance()->getConnection();

    // Pobierz ID użytkownika na podstawie emaila
    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    $userId = $user['id'];

    // 🔒 ROZPOCZĘCIE TRANSAKCJI
    $db->beginTransaction();

    // Usuń powiązania z innymi tabelami (ważne!)
    $db->prepare("DELETE FROM user_training_categories WHERE user_id = :id")->execute(['id' => $userId]);
    $db->prepare("DELETE FROM user_days WHERE user_id = :id")->execute(['id' => $userId]);
    $db->prepare("DELETE FROM user_locations WHERE user_id = :id")->execute(['id' => $userId]);
    $db->prepare("DELETE FROM descriptions WHERE user_id = :id")->execute(['id' => $userId]);
    $db->prepare("DELETE FROM liked_users WHERE user_id = :id OR liked_user_id = :id")->execute(['id' => $userId]);
    $db->prepare("DELETE FROM disliked_users WHERE user_id = :id OR disliked_user_id = :id")->execute(['id' => $userId]);
    $db->prepare("DELETE FROM invitations WHERE sender_id = :id OR receiver_id = :id")->execute(['id' => $userId]);
    $db->prepare("DELETE FROM messages WHERE sender_id = :id OR receiver_id = :id")->execute(['id' => $userId]);

    // Usuń użytkownika
    $db->prepare("DELETE FROM users WHERE id = :id")->execute(['id' => $userId]);

    // ✅ ZATWIERDŹ TRANSAKCJĘ
    $db->commit();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // ❌ COFNIJ WSZYSTKO, JEŚLI WYSTĄPI BŁĄD
    $db->rollBack();
    echo json_encode(['error' => 'Transaction failed: ' . $e->getMessage()]);
}
