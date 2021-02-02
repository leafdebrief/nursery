<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Dashboard;

use App\Domain\Dashboard\DashboardItem;
use App\Domain\Dashboard\DashboardItemNotFoundException;
use App\Domain\Dashboard\DashboardRepository;

class DatabaseDashboardRepository implements DashboardRepository
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
      $stmt = $this->pdo->prepare('SELECT * FROM dashboard');
      $stmt->execute();
      $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Domain\Dashboard\DashboardItem');
      return $stmt->fetchAll();
    }

    /**
     * {@inheritdoc}
     */
    public function findDashboardItemOfId(int $id): DashboardItem
    {
        $stmt = $this->pdo->prepare('SELECT * FROM dashboard WHERE id = ?');
        $stmt->execute([$id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Domain\Dashboard\DashboardItem');
        $dashboard = $stmt->fetch();
        if (!$dashboard) {
          throw new DashboardItemNotFoundException();
        }
        return $dashboard;
    }
}
