<?php

require_once __DIR__.'/User.php';
require_once __DIR__.'/../util/PasswordEncryptor/PasswordEncryptorFactory.php';
require_once __DIR__.'/../util/PasswordEncryptor/PasswordEncryptor.php';
class UserBuilder {
    private User $user;

    public function __construct() {
        $this->user = new User();
    }

    public function setName(string $name): UserBuilder {
        $this->user->name = $name;
        return $this;
    }

    public function setEmail(string $email): UserBuilder {
        $this->user->email = $email;
        return $this;
    }

    public function setPassword(string $password): UserBuilder {
        $this->user->passwordHash = PasswordEncryptorFactory::create()->encrypt($password);
        return $this;
    }

    public function setRole(string $role): UserBuilder {
        $this->user->role = $role;
        return $this;
    }

    public function setAge(int $age): UserBuilder {
        $this->user->age = $age;
        return $this;
    }


    public function build(): User {
        return $this->user;
    }
}
