<?php
declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use App\Domain\Sensor\SensorRepository;
use App\Infrastructure\Persistence\Sensor\DatabaseSensorRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        SensorRepository::class => \DI\autowire(DatabaseSensorRepository::class),
    ]);
};
