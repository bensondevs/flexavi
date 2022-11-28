<?php

use Illuminate\Support\Facades\{Crypt, Hash};

/**
 * Check hashed string match or not
 *
 * @param string $check
 * @param string $hashed
 * @return  bool
 */
if (!function_exists('hash_check')) {
    function hash_check(string $check, string $hashed): bool
    {
        return Hash::check($check, $hashed);
    }
}

/**
 * Alias for "hash_check"
 *
 * @param string $check
 * @param string $hashed
 * @return  bool
 */
if (!function_exists('hashCheck')) {
    function hashCheck(string $check, string $hashed): bool
    {
        return hash_check($check, $hashed);
    }
}

/**
 * Encrypt string
 *
 * @param string $string
 * @return  string
 */
if (!function_exists('encrypt_string')) {
    function encrypt_string(string $string): string
    {
        return Crypt::encryptString($string);
    }
}

/**
 * Alias for "encrypt_string"
 *
 * @param string $string
 * @return  string
 */
if (!function_exists('encryptString')) {
    function encryptString(string $string): string
    {
        return encrypt_string($string);
    }
}

/**
 * Decrypt string
 *
 * @param string $encrypt
 * @return  string
 */
if (!function_exists('decrypt_string')) {
    function decrypt_string(string $encrypted): string
    {
        return Crypt::decryptString($encrypted);
    }
}

/**
 * Alias for "decryptString"
 *
 * @param string $string
 * @return  string
 */
if (!function_exists('decryptString')) {
    function decryptString(string $encrypted): string
    {
        return decrypt_string($encrypted);
    }
}

/**
 * Convert array to JSON string and encrypt
 *
 * @param string $array
 * @return string
 */
if (!function_exists('encrypt_array')) {
    function encrypt_array(array $array): string
    {
        $json = json_encode($array);
        if (!$json) {
            return '';
        }
        return encrypt_string($json);
    }
}

/**
 * Alias for "encryptArray"
 *
 * @param array $array
 * @return  string
 */
if (!function_exists('encryptArray')) {
    function encryptArray(array $array): string
    {
        return encrypt_array($array);
    }
}

/**
 * Decrypt JSON string and convert it to array
 *
 * @param string $encrypted
 * @return  array|null
 */
if (!function_exists('decrypt_array')) {
    function decrypt_array(string $encrypted)
    {
        $decrypted = decrypt_string($encrypted);
        return $decrypted ? json_decode($decrypted, true) : null;
    }
}

/**
 * Alias for "decryptArray"
 *
 * @param string $array
 * @return  string
 */
if (!function_exists('decryptArray')) {
    function decryptArray(string $encrypted)
    {
        return decrypt_array($encrypted);
    }
}
