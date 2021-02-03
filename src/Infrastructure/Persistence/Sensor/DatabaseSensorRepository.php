<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Sensor;

use App\Infrastructure\Persistence\Database;
use App\Domain\Sensor\Sensor;
use App\Domain\Sensor\SensorNotFoundException;
use App\Domain\Sensor\SensorRepository;

class DatabaseSensorRepository extends Database implements SensorRepository
{
  public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
      $stmt = $this->pdo->prepare('SELECT * FROM sensors');
      $stmt->execute();
      $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Domain\Sensor\Sensor');
      return $stmt->fetchAll();
    }

    /**
     * {@inheritdoc}
     */
    public function findSensorOfId(int $id): Sensor
    {
        $stmt = $this->pdo->prepare('SELECT * FROM sensors WHERE id = ?');
        $stmt->execute([$id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Domain\Sensor\Sensor');
        $sensor = $stmt->fetch();
        if (!$sensor) {
          throw new SensorNotFoundException();
        }
        return $sensor;
    }
}
