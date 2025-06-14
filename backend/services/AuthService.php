<?php

use db\Database;

require_once __DIR__ . '/../db/Database.php';

class AuthService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function authenticate(string $email, string $password): ?array
    {
        $stmt = $this->db->prepare("SELECT id, password_hash, role FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return null;
    }

    public function getUserIdByEmail(string $email): ?int
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? intval($result['id']) : null;
    }
}