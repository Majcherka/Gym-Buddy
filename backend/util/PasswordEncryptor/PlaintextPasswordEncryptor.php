<?php

require_once __DIR__ . '/PasswordEncryptor.php';

class PlaintextPasswordEncryptor implements PasswordEncryptor
{
    public function encrypt(string $plain): string
    {
        return $plain;
    }
}