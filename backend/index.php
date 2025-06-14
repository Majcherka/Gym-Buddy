<?php

session_start();

require_once __DIR__ . '/core/Cors.php';
require_once __DIR__ . '/core/Request.php';
require_once __DIR__ . '/core/Response.php';
require_once __DIR__ . '/core/Session.php';

// Apply CORS globally
Cors::apply('*'); // Adjust for your frontend origin

// Controllers
require_once __DIR__ . '/api/UserController.php';
require_once __DIR__ . '/api/AuthController.php';
require_once __DIR__ . '/api/InvitationController.php';
require_once __DIR__ . '/api/GymController.php';
require_once __DIR__ . '/api/MatchController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$routeKey = "$method $uri";

// Routing table
$routes = [

    // User routes
    'POST /api/users'                  => ['UserController', 'create'],
    'POST /api/users/delete'         =>  ['UserController', 'deleteByEmail'],
    'GET /api/users'                  => ['UserController', 'getAll'],
    'GET /api/users/profile'          => ['UserController', 'getProfile'],
    'GET /api/users/random'           => ['UserController', 'getRandomByCategory'],
    'POST /api/users/preferences'     => ['UserController', 'updatePreferences'],

    // Auth routes
    'POST /api/login'                 => ['AuthController', 'login'],
    'POST /api/logout'                => ['AuthController', 'logout'],
    'POST /api/register'              => ['AuthController', 'register'],

    // Invitation routes
    'POST /api/invitations/send'         => ['InvitationController', 'send'],
    'POST /api/invitations/respond'      => ['InvitationController', 'respond'],
    'GET /api/invitations/received'      => ['InvitationController', 'getReceived'],
    'GET /api/invitations/sent-accepted' => ['InvitationController', 'getSentAccepted'],

    // ️ Gym routes
    'GET /api/gyms'                  => ['GymController', 'getAll'],

    // Match actions
    'POST /api/match'                => ['MatchController', 'handleAction'],
];

// Route handling
if (isset($routes[$routeKey])) {
    [$controller, $action] = $routes[$routeKey];
    echo (new $controller())->$action();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
}