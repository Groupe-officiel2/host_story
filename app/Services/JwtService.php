<?php


namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private static string $secret = 'secret';

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
