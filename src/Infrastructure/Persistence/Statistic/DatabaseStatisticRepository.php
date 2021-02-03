<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Statistic;

use App\Infrastructure\Persistence\Database;
use App\Domain\Statistic\Statistic;
use App\Domain\Statistic\StatisticNotFoundException;
use App\Domain\Statistic\StatisticInvalidTableException;
use App\Domain\Statistic\StatisticInvalidRangeException;
use App\Domain\Statistic\StatisticRepository;

use App\Domain\Sensor\SensorNotFoundException;

class DatabaseStatisticRepository extends Database implements StatisticRepository
{
  public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(string $table): array
    {
        $this->validateTableName($table);
        $stmt = $this->pdo->prepare("SELECT * FROM $table");
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Domain\Statistic\Statistic');
        return $stmt->fetchAll();
    }

    /**
     * {@inheritdoc}
     */
    public function findStatisticOfId(string $table, int $id): Statistic
    {
        $this->validateTableName($table);
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Domain\Statistic\Statistic');
        $statistic = $stmt->fetch();
        if (!$statistic) {
          throw new StatisticNotFoundException();
        }
        return $statistic;
    }

    /**
     * {@inheritdoc}
     */
    public function getAverageFor(string $table, string $range): float
    {
        $this->validateTableName($table);
        $this->validateRange($range);
        $query = "SELECT AVG(`value`) FROM $table";
        switch ($range) {
          case 'day':
            $query .= " WHERE DAYOFWEEK(`timestamp`) = DAYOFWEEK(NOW())";
            break;
          case 'hour':
            $query .= " WHERE HOUR(`timestamp`) = HOUR(NOW())";
            break;
          case 'minute':
            $query .= " WHERE HOUR(`timestamp`) = HOUR(NOW())AND LENGTH(MINUTE(`timestamp`)) = LENGTH(MINUTE(NOW())) AND LEFT(MINUTE(`timestamp`), 1) = LEFT(MINUTE(NOW()), 1)";
            break;
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $statistic = $stmt->fetchColumn();
        return $statistic;
    }

    public function getCurrentStatistic(string $table): Statistic {
        $path = "/home/pi/nursery/scripts/read$table.py";
        if (is_readable($path)) {
          try {
            $command = escapeshellcmd("sudo python3 $path");
            $output = shell_exec($command);
            return new Statistic(json_decode($output));
          } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
          }
        } else {
          throw new SensorNotFoundException();
        }
    } 

    private function validateTableName(string $table): void {
      if (!in_array($table, ['humidity', 'temperature'])) {
          throw new StatisticInvalidTableException();
      }
    }

    private function validateRange(string $range): void {
      if (!in_array($range, ['minute', 'hour', 'day'])) {
          throw new StatisticInvalidRangeException();
      }
    }
}
