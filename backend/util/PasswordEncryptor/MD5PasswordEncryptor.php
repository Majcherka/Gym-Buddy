<?php

require_once __DIR__ . '/PasswordEncryptor.php';

class MD5PasswordEncryptor implements PasswordEncryptor
{
    public function encrypt(string $plain): string
    {
        return md5($plain);
    }
}