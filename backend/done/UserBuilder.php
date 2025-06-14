<?php

namespace done;

class UserBuilder
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function setName(string $name): UserBuilder
    {
        $this->user->name = $name;
        return $this;
    }

    public function setEmail(string $email): UserBuilder
    {
        $this->user->email = $email;
        return $this;
    }

    public function setPassword(string $password): UserBuilder
    {
        $this->user->passwordHash = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    public function setRole(string $role): UserBuilder
    {
        $this->user->role = $role;
        return $this;
    }

    public function setAge(int $age): UserBuilder
    {
        $this->user->age = $age;
        return $this;
    }


    public function build(): User
    {
        return $this->user;
    }
}
