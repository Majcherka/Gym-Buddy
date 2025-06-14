<?php

require_once 'Database.php';
require_once 'Message.php';
require_once 'MessageFactory.php';
session_start();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($method === 'GET') {
    if (!isset($_GET['u1'], $_GET['u2'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing user ids']);
        exit;
    }

    $messages = Message::getConversation((int)$_GET['u1'], (int)$_GET['u2']);
    echo json_encode($messages);

} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['receiver_id'], $data['content'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    $message = MessageFactory::create($_SESSION['user_id'], (int)$data['receiver_id'], $data['content']);

    if ($message->save()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Message not saved']);
    }

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
