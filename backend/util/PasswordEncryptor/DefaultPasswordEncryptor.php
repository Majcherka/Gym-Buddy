<?php

require_once __DIR__ . '/PasswordEncryptor.php';

class DefaultPasswordEncryptor implements PasswordEncryptor
{
    public function encrypt(string $plain): string
    {
        return password_hash($plain, PASSWORD_DEFAULT);
    }
}