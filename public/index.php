<?php

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ResponseInterface;

use function FastRoute\simpleDispatcher;

require_once "../vendor/autoload.php";
$container = require_once "../config/dependency.php";

$dispatcher = simpleDispatcher(
    require_once "../routes/api.php"
);

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

$psr17Factory = new Psr17Factory();

$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$request = $creator->fromGlobals();

header("Content-type: application/json");

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode([
            'error' => 'Rota não encontrada'
        ]);
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo json_encode([
            'error' => 'Método não permitido',
            'allowed_methods' => $routeInfo[1]
        ]);
        break;

    case FastRoute\Dispatcher::FOUND:
        [$controllerClass, $method] = $routeInfo[1];
        $vars = $routeInfo[2];

        try {
            $controller = $container->get($controllerClass);

            $response = call_user_func_array([$controller, $method], [$request, $vars]);

            if ($response instanceof ResponseInterface) {
                http_response_code($response->getStatusCode());

                foreach ($response->getHeaders() as $name => $values) {
                    foreach ($values as $value) {
                        header(sprintf('%s: %s', $name, $value), false);
                    }
                }

                echo (string) $response->getBody();
            } elseif (is_array($response) || is_object($response)) {
                header("Content-Type: application/json");
                echo json_encode($response);
            } elseif (is_string($response)) {
                echo $response;
            } else {
                echo json_encode(['success' => true]);
            }

        } catch (Throwable $e) {
            http_response_code(500);
            header("Content-Type: application/json");
            echo json_encode([
                'error' => 'Erro interno do servidor',
                'message' => $e->getMessage()
            ]);
        }

        break;
}