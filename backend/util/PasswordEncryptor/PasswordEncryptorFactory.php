<?php

require_once __DIR__ . '/MD5PasswordEncryptor.php';
require_once __DIR__ . '/DefaultPasswordEncryptor.php';
require_once __DIR__ . '/PlaintextPasswordEncryptor.php';

class PasswordEncryptorFactory
{
    public static function create(): PasswordEncryptor
    {
        $config = require __DIR__ . '/../../config/app.php';
        $strategy = $config['password_encryptor'] ?? 'default';

        return match ($strategy) {
            'md5' => new MD5PasswordEncryptor(),
            'plaintext' => new PlaintextPasswordEncryptor(),
            'default' => new DefaultPasswordEncryptor(),
            default => new DefaultPasswordEncryptor()
        };
    }
}