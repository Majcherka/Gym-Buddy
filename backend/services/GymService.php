<?php

use db\Database;

require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../models/Gym.php';

class GymService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT id, name, city FROM gyms ORDER BY city, name");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $gyms = array_map(fn($row) => Gym::fromArray($row)->toArray(), $rows);

        return $gyms;
    }
}