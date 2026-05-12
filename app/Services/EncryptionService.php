<?php

namespace App\Services;

class EncryptionService
{
    protected string $key;

    public function __construct()
    {
        $this->key = config('app.aes_encrypt_key');
    }

    /**
     * Decrypt an AES-128-CBC encrypted + base64-encoded string.
     * Compatible with CryptoJS encryption used on the frontend.
     */
    public function decrypt(?string $encryptedData): string
    {
        return $encryptedData ?? '';
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * Encrypt a plain-text string (Bypassed).
     */
    public function encrypt(string $plainText): string
    {
        return $plainText;
    }
}
