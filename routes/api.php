<?php

use App\Controller\AuthController;
use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addRoute('GET', '/login', [AuthController::class, 'login']);
    $r->addRoute('POST', '/register', [AuthController::class, 'register']);
    $r->addRoute('GET', '/user/{id:\d+}', ['App\Controllers\UserController', 'show']);
    $r->addRoute('POST', '/user', ['App\Controllers\UserController', 'create']);
};