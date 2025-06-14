<?php

use db\Database;

class User {
    public int $id;
    public string $name;
    public string $email;
    public string $passwordHash;
    public string $role;
    public int $age;

    public static function getByEmail(string $email): ?done\User {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;

        $user = new done\User();
        $user->id = $data['id'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->passwordHash = $data['password_hash'];
        $user->role = $data['role'];
        $user->age = $data['age'];
        return $user;
    }

    public function save(): bool {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO users (name, email, password_hash,age, role) VALUES (:name, :email, :password, :age, :role)");
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->passwordHash);
        $stmt->bindParam(':role', $this->role);
        return $stmt->execute([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->passwordHash,
            'age' => $this->age,
            'role' => $this->role
        ]);
    }
}
