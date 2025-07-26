<?php

use App\Infra\ConnectionFactory;
use DI\ContainerBuilder;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    PDO::class => function (): PDO {
        return ConnectionFactory::CreateConnection();
    },
]);

$container = $builder->build();

return $container;
