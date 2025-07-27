<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;

class AuthController
{
    public function __construct()
    {}

    public function register(ServerRequestInterface $request)
    {
        $data = ['teste' => 1];

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );
    }
}
