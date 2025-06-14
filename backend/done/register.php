<?php

use db\Database;
use done\UserBuilder;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');

require_once 'Database.php';
require_once 'User.php';
require_once 'UserBuilder.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (
        !$data ||
        !isset($data['name'], $data['email'], $data['password'], $data['age'])
    ) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    $age = intval($data['age']);
    if ($age < 18) {
        http_response_code(400);
        echo json_encode(['error' => 'You must be at least 18 years old to register.']);
        exit;
    }

    $user = (new UserBuilder())
        ->setName($data['name'])
        ->setEmail($data['email'])
        ->setPassword($data['password'])
        ->setAge($age)  // <--- dodane!
        ->setRole('user')
        ->build();

    if ($user->save()) {
        // echo json_encode(['success' => true]);
        // Pobierz ID nowego użytkownika
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $user->email]);
        $createdUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($createdUser) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $user->email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $userId = $result ? $result['id'] : null;

            echo json_encode([
                'success' => true,
                'user_id' => $userId,
                'redirect' => 'setup_profile.html'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'User created but ID fetch failed']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Could not register user']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
