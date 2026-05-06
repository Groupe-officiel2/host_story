<?php


namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private static string $secret = 'your_very_long_secret_key_123456789';

    public static function generateToken($userId, $role = 'user')
    {
        $payload = [
            'sub' => $userId,
            'role' => $role,
            'iat' => time(),
            'exp' => time() + 3600
        ];

        return JWT::encode($payload, self::$secret, 'HS256');
    }
}
