<?php

require_once __DIR__ . '/../services/GymService.php';
require_once __DIR__ . '/../core/Response.php';

class GymController
{
    private GymService $service;

    public function __construct()
    {
        $this->service = new GymService();
    }

    public function getAll(): string
    {
        try {
            return Response::json($this->service->getAll());
        } catch (Exception $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }
}