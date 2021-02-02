<?php
declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Domain\Sensor\SensorRepository;
use App\Domain\Statistic\StatisticRepository;
use App\Domain\Spectrum\SpectralRepository;
use App\Domain\Snapshot\SnapshotRepository;
use App\Domain\Module\ModuleRepository;
use App\Domain\Dashboard\DashboardRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use App\Infrastructure\Persistence\Sensor\DatabaseSensorRepository;
use App\Infrastructure\Persistence\Statistic\DatabaseStatisticRepository;
use App\Infrastructure\Persistence\Spectrum\DatabaseSpectralRepository;
use App\Infrastructure\Persistence\Module\DatabaseModuleRepository;
use App\Infrastructure\Persistence\Dashboard\DatabaseDashboardRepository;
use App\Infrastructure\Transients\Snapshot\TransientSnapshotRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        SensorRepository::class => \DI\autowire(DatabaseSensorRepository::class),
        StatisticRepository::class => \DI\autowire(DatabaseStatisticRepository::class),
        SpectralRepository::class => \DI\autowire(DatabaseSpectralRepository::class),
        SnapshotRepository::class => \DI\autowire(TransientSnapshotRepository::class),
        ModuleRepository::class => \DI\autowire(DatabaseModuleRepository::class),
        DashboardRepository::class => \DI\autowire(DatabaseDashboardRepository::class),
    ]);
};
