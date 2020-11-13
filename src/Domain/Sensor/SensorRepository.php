<?php
declare(strict_types=1);

namespace App\Domain\Sensor;

interface SensorRepository
{
    /**
     * @return Sensor[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Sensor
     * @throws SensorNotFoundException
     */
    public function findSensorOfId(int $id): Sensor;
}
