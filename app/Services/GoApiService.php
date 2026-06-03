<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use App\DTO\CreateServerDTO;

class GoApiService
{
    private string $secretKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET', 'secret');
        $this->apiUrl = env('GO_API_URL', 'http://hoststory-api:8082');
    }

    public function generateJwt(string $userId, string $role = 'user'): string
    {
        $payload = [
            'sub' => $userId,
            'role' => $role,
            'exp' => time() + (24 * 60 * 60)
        ];
        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function createServer(CreateServerDTO $dto, string $userId): mixed
    {
        $token = JwtService::generateToken($userId, 'admin');
        $url = "{$this->apiUrl}/template";
        $slotsCount = request()->input('slots') ?? $dto->slots ?? 2;

        $response = Http::withToken($token)
            ->get($url, [
                'image'   => $dto->image,
                'players' => $slotsCount,
                'name'    => $dto->name,
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('API création serveur: ' . $response->body());
        }

        return $response->json();
    }
}