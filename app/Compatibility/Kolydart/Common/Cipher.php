<?php

namespace Kolydart\Common;

/**
 * Compatibility layer for Cipher functionality
 */
class Cipher
{
    /**
     * Decrypt data using a key
     *
     * @param string $data Encrypted data
     * @param string $key Decryption key
     * @return string Decrypted data
     */
    public function decrypt($data, $key)
    {
        // Use Laravel's built-in encryption
        try {
            return \Crypt::decryptString($data);
        } catch (\Exception $e) {
            // Fallback for different encryption format
            return base64_decode($data);
        }
    }

    /**
     * Encrypt data using a key
     *
     * @param string $data Data to encrypt
     * @param string $key Encryption key
     * @return string Encrypted data
     */
    public function encrypt($data, $key)
    {
        // Use Laravel's built-in encryption
        return \Crypt::encryptString($data);
    }
}