<?php

use done\UserBuilder;

require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../core/Request.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Session.php';

class AuthController
{
    private AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    public function login(): string
    {
        Session::start();

        $data = Request::json();

        if (!isset($data['email'], $data['password'])) {
            return Response::json(['error' => 'Missing email or password'], 400);
        }

        $user = $this->auth->authenticate($data['email'], $data['password']);

        if ($user) {
            Session::set('user_id', $user['id']);
            Session::set('role', $user['role']);

            return Response::json([
                'success' => true,
                'user_id' => $user['id'],
                'role' => $user['role']
            ]);
        }

        return Response::json(['error' => 'Invalid credentials'], 401);
    }

    public function logout(): string
    {
        Session::start();
        Session::destroy();

        return Response::json(['success' => true]);
    }

    public function register(): string
    {
        $data = Request::json();

        if (!isset($data['name'], $data['email'], $data['password'], $data['age'])) {
            return Response::json(['error' => 'Missing fields'], 400);
        }

        if (intval($data['age']) < 18) {
            return Response::json(['error' => 'You must be at least 18 years old to register.'], 400);
        }

        $user = (new UserBuilder())
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setPassword($data['password'])
            ->setAge($data['age'])
            ->setRole('user')
            ->build();

        if (!$user->save()) {
            return Response::json(['error' => 'Could not register user'], 500);
        }

        $userId = $this->auth->getUserIdByEmail($user->email);

        if ($userId) {
            return Response::json([
                'success' => true,
                'user_id' => $userId,
                'redirect' => 'setup_profile.html'
            ]);
        }

        return Response::json(['error' => 'User created but ID fetch failed'], 500);
    }
}