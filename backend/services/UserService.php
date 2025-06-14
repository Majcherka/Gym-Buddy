<?php

use db\Database;

require_once __DIR__ . '/../db/Database.php';

class UserService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function deleteByEmail(string $email): bool
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) return false;

            $id = $user['id'];

            $tables = [
                'user_training_categories',
                'user_days',
                'user_locations',
                'descriptions',
                'liked_users',
                'disliked_users',
                'invitations',
                'messages'
            ];

            foreach ($tables as $table) {
                $sql = "DELETE FROM $table WHERE user_id = :id OR sender_id = :id OR receiver_id = :id OR liked_user_id = :id OR disliked_user_id = :id";
                $this->db->prepare($sql)->execute(['id' => $id]);
            }

            $this->db->prepare("DELETE FROM users WHERE id = :id")->execute(['id' => $id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getAllUsers(): array
    {
        $stmt = $this->db->query("SELECT id, name, email, age, role FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProfile(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT name, age FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return null;

        return [
            'name' => $user['name'],
            'age' => $user['age'],
            'categories' => $this->fetchArray("SELECT tc.name FROM user_training_categories utc JOIN training_categories tc ON utc.category_id = tc.id WHERE utc.user_id = :user_id", $userId),
            'days' => $this->fetchArray("SELECT d.name FROM user_days ud JOIN days d ON ud.day_id = d.id WHERE ud.user_id = :user_id", $userId),
            'city' => $this->fetchColumn("SELECT l.name FROM user_locations ul JOIN locations l ON ul.location_id = l.id WHERE ul.user_id = :user_id", $userId),
            'description' => $this->fetchColumn("SELECT description FROM descriptions WHERE user_id = :user_id", $userId)
        ];
    }

    public function getRandomUserByCategory(string $category): ?array
    {
        $stmt = $this->db->prepare("
            SELECT 
                u.id, u.name, u.email, u.age, d.description,
                (
                    SELECT string_agg(tc.name, ',' ORDER BY tc.id)
                    FROM user_training_categories utc
                    JOIN training_categories tc ON utc.category_id = tc.id
                    WHERE utc.user_id = u.id
                ) AS categories,
                (
                    SELECT string_agg(name, ',') FROM (
                        SELECT dy.name
                        FROM user_days ud
                        JOIN days dy ON ud.day_id = dy.id
                        WHERE ud.user_id = u.id
                        ORDER BY day_id
                    )
                ) AS availability,
                (
                    SELECT string_agg(l.name, ',' ORDER BY l.name)
                    FROM user_locations ul
                    JOIN locations l ON ul.location_id = l.id
                    WHERE ul.user_id = u.id
                ) AS locations
            FROM users u
            LEFT JOIN descriptions d ON u.id = d.user_id
            WHERE EXISTS (
                SELECT 1 FROM user_training_categories utc
                JOIN training_categories tc ON utc.category_id = tc.id
                WHERE utc.user_id = u.id AND tc.name = :category
            )
            ORDER BY random()
            LIMIT 1;
        ");
        $stmt->execute(['category' => $category]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function updatePreferences(array $data): void
    {
        $userId = $data['user_id'];

        $this->db->beginTransaction();

        // clear
        $this->db->prepare("DELETE FROM user_training_categories WHERE user_id = :user_id")->execute(['user_id' => $userId]);
        $this->db->prepare("DELETE FROM user_days WHERE user_id = :user_id")->execute(['user_id' => $userId]);
        $this->db->prepare("DELETE FROM user_locations WHERE user_id = :user_id")->execute(['user_id' => $userId]);
        $this->db->prepare("DELETE FROM descriptions WHERE user_id = :user_id")->execute(['user_id' => $userId]);

        // insert categories
        foreach ($data['categories'] as $cat) {
            $stmt = $this->db->prepare("SELECT id FROM training_categories WHERE name = :name");
            $stmt->execute(['name' => $cat]);
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->db->prepare("INSERT INTO user_training_categories (user_id, category_id) VALUES (:user_id, :category_id)")
                    ->execute(['user_id' => $userId, 'category_id' => $row['id']]);
            }
        }

        // insert days
        foreach ($data['days'] as $day) {
            $stmt = $this->db->prepare("SELECT id FROM days WHERE name = :name");
            $stmt->execute(['name' => $day]);
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->db->prepare("INSERT INTO user_days (user_id, day_id) VALUES (:user_id, :day_id)")
                    ->execute(['user_id' => $userId, 'day_id' => $row['id']]);
            }
        }

        // insert location
        $stmt = $this->db->prepare("SELECT id FROM locations WHERE name = :name");
        $stmt->execute(['name' => $data['city']]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->db->prepare("INSERT INTO user_locations (user_id, location_id) VALUES (:user_id, :location_id)")
                ->execute(['user_id' => $userId, 'location_id' => $row['id']]);
        }

        // insert description
        $this->db->prepare("INSERT INTO descriptions (user_id, description) VALUES (:user_id, :description)")
            ->execute(['user_id' => $userId, 'description' => $data['description']]);

        $this->db->commit();
    }

    private function fetchArray(string $sql, int $userId): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function fetchColumn(string $sql, int $userId): ?string
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn() ?: null;
    }
}


