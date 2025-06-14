<?php
session_start();

// Najpierw poprawne nagłówki CORS
header("Access-Control-Allow-Origin: http://localhost:63342");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Obsługa preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Kasuj sesję użytkownika
session_unset();
session_destroy();

echo json_encode(['success' => true]);
