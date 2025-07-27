<?php

use App\Controller\AuthController;
use App\Middleware\AuthMiddleware;
use App\Services\JwtServices;
use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addRoute('GET',    '/login',    [AuthController::class, 'login', false]);
    $r->addRoute('POST',   '/register', [AuthController::class, 'register', false]);
    $r->addRoute('GET',    '/user/{id:\d+}', ['App\Controllers\UserController', 'show', true]);
    $r->addRoute('POST',   '/user', ['App\Controllers\UserController', 'create', true]);
};