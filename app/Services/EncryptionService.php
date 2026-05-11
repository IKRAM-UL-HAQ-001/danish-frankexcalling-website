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
        if (empty($encryptedData)) {
            return '';
        }

        // Zero IV — matches the frontend CryptoJS implementation
        $iv = hex2bin('00000000000000000000000000000000');

        $decoded = base64_decode($encryptedData, true);

        if ($decoded === false) {
            return '';
        }

        $decrypted = openssl_decrypt(
            $decoded,
            'AES-128-CBC',
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return $decrypted !== false ? $decrypted : '';
    }

    /**
     * Encrypt a plain-text string using AES-128-CBC.
     * Returns a base64-encoded ciphertext compatible with CryptoJS.
     */
    public function encrypt(string $plainText): string
    {
        // Zero IV — kept for CryptoJS frontend compatibility
        $iv = hex2bin('00000000000000000000000000000000');

        $encrypted = openssl_encrypt(
            $plainText,
            'AES-128-CBC',
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_encode($encrypted);
    }
}
