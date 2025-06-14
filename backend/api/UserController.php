<?php

require_once __DIR__ . '/../models/UserBuilder.php';

require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../core/Request.php';
require_once __DIR__ . '/../core/Response.php';

class UserController
{
    private UserService $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function create()
    {
        $data = Request::json();
        if (!isset($data['name'], $data['email'], $data['age'], $data['role'], $data['password'])) {
            return Response::json(['error' => 'Missing fields'], 400);
        }

        $user = (new UserBuilder())
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setPassword($data['password'])
            ->setRole($data['role'])
            ->setAge($data['age'])
            ->build();

        if ($user->save()) {
            return Response::json(['success' => true]);
        }

        return Response::json(['error' => 'Failed to save user'], 500);
    }

    public function deleteByEmail()
    {
        $data = Request::json();
        if (!isset($data['email'])) {
            return Response::json(['error' => 'Missing email'], 400);
        }

        $success = $this->service->deleteByEmail($data['email']);
        return $success
            ? Response::json(['success' => true])
            : Response::json(['error' => 'Deletion failed'], 500);
    }

    public function getAll()
    {
        return Response::json($this->service->getAllUsers());
    }

    public function getProfile()
    {
        $userId = $_GET['user_id'] ?? null;
        if (!$userId) {
            return Response::json(['error' => 'Missing user_id'], 400);
        }

        $profile = $this->service->getProfile(intval($userId));
        return $profile
            ? Response::json($profile)
            : Response::json(['error' => 'User not found'], 404);
    }

    public function getRandomByCategory()
    {
        $category = $_GET['category'] ?? null;
        if (!$category) {
            return Response::json(['error' => 'Missing category'], 400);
        }

        $user = $this->service->getRandomUserByCategory($category);
        return $user
            ? Response::json(['success' => true, 'user' => $user])
            : Response::json(['success' => false, 'error' => 'No users found']);
    }

    public function updatePreferences()
    {
        $data = Request::json();

        if (!isset($data['categories'], $data['days'], $data['city'], $data['user_id'], $data['description'])) {
            return Response::json(['error' => 'Missing required fields'], 400);
        }

        try {
            $this->service->updatePreferences($data);
            return Response::json(['success' => true]);
        } catch (Exception $e) {
            return Response::json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}