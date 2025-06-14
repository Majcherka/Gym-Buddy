<?php

require_once __DIR__ . '/../services/InvitationService.php';
require_once __DIR__ . '/../core/Request.php';
require_once __DIR__ . '/../core/Response.php';

class InvitationController
{
    private InvitationService $service;

    public function __construct()
    {
        $this->service = new InvitationService();
    }

    public function send(): string
    {
        $data = Request::json(); // read data from request

        if (!isset($data['sender_id'], $data['receiver_id'], $data['gym_id'], $data['message'])) {
            return Response::json(['error' => 'Missing required fields'], 400);
        }

        try {
            $this->service->send($data);
            return Response::json(['success' => true]);
        } catch (Exception $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }

    public function respond(): string
    {
        $data = Request::json();
        if (!isset($data['invitation_id'], $data['status']) || !in_array($data['status'], ['accepted', 'rejected'])) {
            return Response::json(['error' => 'Invalid input'], 400);
        }

        $this->service->respond($data['invitation_id'], $data['status']);
        return Response::json(['success' => true]);
    }

    public function getReceived(): string
    {
        $userId = $_GET['user_id'] ?? null;
        $status = $_GET['status'] ?? 'pending';

        if (!$userId) return Response::json([], 200);

        $allowed = ['pending', 'accepted', 'rejected'];
        if (!in_array($status, $allowed)) {
            return Response::json(['error' => 'Invalid status'], 400);
        }

        $result = $this->service->getReceived($userId, $status);
        return Response::json($result);
    }

    public function getSentAccepted(): string
    {
        $userId = $_GET['user_id'] ?? null;
        if (!$userId) return Response::json([], 200);

        $result = $this->service->getSentAccepted($userId);
        return Response::json($result);
    }
}