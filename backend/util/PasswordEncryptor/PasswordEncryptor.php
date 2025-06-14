<?php

interface PasswordEncryptor
{
    public function encrypt(string $plain): string;
}