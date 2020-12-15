<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Statistic;

use App\Domain\Statistic\Statistic;
use App\Domain\Statistic\StatisticNotFoundException;
use App\Domain\Statistic\StatisticInvalidTableException;
use App\Domain\Statistic\StatisticInvalidRangeException;
use App\Domain\Statistic\StatisticRepository;

class DatabaseStatisticRepository implements StatisticRepository
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * DatabaseStatisticRepository constructor.
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
