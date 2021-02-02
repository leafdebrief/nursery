<?php

declare(strict_types=1);

use App\Application\Actions\Sensor\ListSensorsAction;
use App\Application\Actions\Sensor\ViewSensorAction;
use App\Application\Actions\Statistic\ListStatisticsAction;
use App\Application\Actions\Statistic\ViewStatisticAction;
use App\Application\Actions\Statistic\AverageStatisticAction;
use App\Application\Actions\Statistic\CurrentStatisticAction;
use App\Application\Actions\Spectrum\ListSpectraAction;
use App\Application\Actions\Spectrum\ViewSpectrumAction;
use App\Application\Actions\Snapshot\GetSnapshotAction;
use App\Application\Actions\Module\ListModulesAction;
use App\Application\Actions\Module\ViewModuleAction;
use App\Application\Actions\Dashboard\ListDashboardItemsAction;
use App\Application\Actions\Dashboard\ViewDashboardItemAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\App;

return function (App $app) {
  $app->options('/{routes:.*}', function (Request $request, Response $response) {
    // CORS Pre-Flight OPTIONS Request Handler
    return $response;
  });

  $app->get('/', function (Request $request, Response $response) {
    $payload = file_get_contents('/home/pi/nursery/public/home/index.html');
    $response->getBody()->write($payload);
    return $response;
  });

  $app->group('/test', function (Group $group) {
    $group->get('', function (Request $request, Response $response) {
      $payload = json_encode([
        'ip' => $_SERVER['SERVER_ADDR'],
        'hostname' => $_SERVER['SERVER_NAME']
      ]);
      $response
        ->withHeader('Content-Type', 'text/html')
        ->getBody()
        ->write($payload);
      return $response;
    });
    
    $group->get('/script', function (Request $request, Response $response) {
      $command = escapeshellcmd('/usr/bin/env python3 /home/pi/nursery/scripts/test.py');
      $output = shell_exec($command);
      $response
        ->withHeader('Content-Type', 'application/json')
        ->getBody()
        ->write($output);
      return $response;
    });
  });

  $app->group('/api', function (Group $group) {
    $group->get('/snapshot', GetSnapshotAction::class);

    $group->group('/sensors', function (Group $group) {
      $group->get('', ListSensorsAction::class);
      $group->get('/{id}', ViewSensorAction::class);
    });

    $group->group('/modules', function (Group $group) {
      $group->get('', ListModulesAction::class);
      $group->get('/{id}', ViewModuleAction::class);
    });

    $group->group('/dashboard', function (Group $group) {
      $group->get('', ListDashboardItemsAction::class);
      $group->get('/{id}', ViewDashboardItemAction::class);
    });

    $group->group('/statistics', function (Group $group) {
      $group->get('/{table}', ListStatisticsAction::class);
      $group->get('/{table}/current', CurrentStatisticAction::class);
      $group->get('/{table}/{id}', ViewStatisticAction::class);
      $group->get('/{table}/average/{range}', AverageStatisticAction::class);
    });

    $group->group('/spectra', function (Group $group) {
      $group->get('', ListSpectraAction::class);
      $group->get('/{id}', ViewSpectrumAction::class);
    });
  })->add(function (Request $request, RequestHandler $handler) {
    // CORS middleware
    $response = $handler->handle($request);
    return $response
      ->withHeader('Referrer-Policy', 'no-referrer-when-downgrade')
      ->withHeader('Access-Control-Allow-Origin', '*')
      ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
      ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
  })->add(function (Request $request, RequestHandler $handler) {
    // trailing slash middleware
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
      $path = rtrim($path, '/');
      $uri = $uri->withPath($path);
      if ($request->getMethod() == 'GET') {
        $response = new Response();
        return $response
          ->withHeader('Location', (string) $uri)
          ->withStatus(301);
      } else {
        $request = $request->withUri($uri);
      }
    }
    return $handler->handle($request);
  });

  $app->add(function (Request $request, RequestHandler $handler) {
    // trailing slash middleware
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
      $path = rtrim($path, '/');
      $uri = $uri->withPath($path);
      if ($request->getMethod() == 'GET') {
        $response = new Response();
        return $response
          ->withHeader('Location', (string) $uri)
          ->withStatus(301);
      } else {
        $request = $request->withUri($uri);
      }
    }
    return $handler->handle($request);
  });
};
