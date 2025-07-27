<?php

namespace App\Middleware;

use App\Services\JwtServices;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware
{
    public function __construct(private JwtServices $jwtServices)
    {}

    public function proccess(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $authHeader = $request->getHeaderLine("Authorization");

        if(!$authHeader || !str_starts_with($authHeader, "Baerer ")) {
            return new Response(401, [], json_encode(["error" => "Não autenticado"]));
        }        

        $token = trim(str_replace("Baerer ", "", $authHeader));
        try{
            $decoded = $this->jwtServices->validateJwt($token);
        }catch(Exception $erro) {
            return new Response(401, [], json_encode(['error' => 'Não autorizado']));
        }

        $request = $request->withAttribute('user', $decoded);

        return $handler->handle($request);
    }
}