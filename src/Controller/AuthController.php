<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;

class AuthController
{
    public function register(ServerRequestInterface $request)
    {
        echo "<pre>"; var_dump($request->getQueryParams()); exit;
    }
}