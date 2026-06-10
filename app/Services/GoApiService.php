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
        // Le secret est récupéré depuis la variable d'environnement JWT_SECRET (ou 'secret' par défaut)
        $this->secretKey = env('JWT_SECRET', 'secret');
        // L'URL de l'API Go
        $this->apiUrl = env('GO_API_URL', 'http://host.docker.internal:8082');
    }

    /**
     * Génère un token JWT pour interagir avec l'API Go
     */
    public function generateJwt(string $userId, string $role = 'user'): string
    {
        $payload = [
            'sub' => $userId,
            'role' => $role,
            'exp' => time() + (24 * 60 * 60) // Expire dans 24h
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    /**
     * Envoie la requête à l'API Go pour créer un serveur
     */
    public function createServer(CreateServerDTO $dto, string $userId): mixed
    {
        // On génère le token (on peut mettre 'admin' si l'API l'exige)
        $token = $this->generateJwt($userId, 'admin');

        $url = "{$this->apiUrl}/template";

        // L'appel curl que tu as fourni était un GET avec des paramètres d'URL (ou POST sans body).
        // Le comportement par défaut de curl sans '-X' est GET. 
        // Si ton API attend un POST, tu peux remplacer get() par post()
        $response = Http::withToken($token)
            ->get($url, [
                'image' => $dto->image,
                'players' => $dto->players,
                'name' => $dto->name,
            ]);

        return $response->json();
    }
}
