<?php

class Gym
{
    public int $id;
    public string $name;
    public string $city;

    public static function fromArray(array $row): Gym
    {
        $gym = new self();
        $gym->id = $row['id'];
        $gym->name = $row['name'];
        $gym->city = $row['city'];
        return $gym;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city
        ];
    }
}