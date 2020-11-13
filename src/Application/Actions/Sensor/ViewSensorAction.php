<?php
declare(strict_types=1);

namespace App\Application\Actions\Sensor;

use Psr\Http\Message\ResponseInterface as Response;

class ViewSensorAction extends SensorAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $sensorId = (int) $this->resolveArg('id');
        $sensor = $this->sensorRepository->findSensorOfId($sensorId);

        $this->logger->info("Sensor of id `${sensorId}` was viewed.");

        return $this->respondWithData($sensor);
    }
}
