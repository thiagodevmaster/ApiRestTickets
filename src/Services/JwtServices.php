<?php

namespace App\Services;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require __DIR__ . "/../../config/config.php";

class JwtServices
{
    protected string $key = JWT_KEY;

    public function generateJwt(array $payload = []) 
    {
        $now = new DateTimeImmutable();

        $defaultPayload = [
            'iat' => $now->getTimestamp(),
            'nbf' => $now->getTimestamp(),
            'exp' => $now->modify('+1 hour')->getTimestamp(),
            'iss' => '',
            'aud' => '',
        ];

        $token = JWT::encode(array_merge($defaultPayload, $payload), $this->key, 'HS256');

        return $token;
    }

    public function validateJwt(string $token): array | object
    {
        return JWT::decode($token, new Key($this->key, 'HS256')); 
    }

}