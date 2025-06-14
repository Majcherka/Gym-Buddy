<?php

require_once __DIR__ . '/../core/Request.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../services/MatchService.php';

class MatchController
{
    private MatchService $service;

    public function __construct()
    {
        $this->service = new MatchService();
    }

    public function handleAction(): string
    {
        $data = Request::json();

        if (!isset($data['user_id'], $data['target_id'], $data['action'])) {
            return Response::json(['error' => 'Missing required fields'], 400);
        }

        $success = $this->service->recordAction(
            intval($data['user_id']),
            intval($data['target_id']),
            $data['action']
        );

        return $success
            ? Response::json(['success' => true])
            : Response::json(['error' => 'Invalid action or failed operation'], 400);
    }
}