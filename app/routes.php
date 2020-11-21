<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\Sensor\ListSensorsAction;
use App\Application\Actions\Sensor\ViewSensorAction;
use App\Application\Actions\Statistic\ListStatisticsAction;
use App\Application\Actions\Statistic\ViewStatisticAction;
use App\Application\Actions\Spectrum\ListSpectraAction;
use App\Application\Actions\Spectrum\ViewSpectrumAction;
use App\Application\Actions\Snapshot\GetSnapshotAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', GetSnapshotAction::class);

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->group('/sensors', function (Group $group) {
        $group->get('', ListSensorsAction::class);
        $group->get('/{id}', ViewSensorAction::class);
    });

    $app->group('/statistics', function (Group $group) {
        $group->get('/{table}', ListStatisticsAction::class);
        $group->get('/{table}/{id}', ViewStatisticAction::class);
    });

    $app->group('/spectra', function (Group $group) {
        $group->get('', ListSpectraAction::class);
        $group->get('/{id}', ViewSpectrumAction::class);
    });
};
