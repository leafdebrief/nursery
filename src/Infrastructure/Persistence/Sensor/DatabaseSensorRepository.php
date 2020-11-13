<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Sensor;

use App\Domain\Sensor\Sensor;
use App\Domain\Sensor\SensorNotFoundException;
use App\Domain\Sensor\SensorRepository;

class DatabaseSensorRepository implements SensorRepository
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * InMemoryUserRepository constructor.
     *
     * @param array|null $users
     */
    public function __construct()
    {
        
        $dsn = "mysql:host=localhost;dbname=nursery;charset=utf8mb4";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new \PDO($dsn, 'root', 'kl99l9jk', $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
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
