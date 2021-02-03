<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Dashboard;

use App\Infrastructure\Persistence\Database;
use App\Domain\Dashboard\DashboardItem;
use App\Domain\Dashboard\DashboardItemNotFoundException;
use App\Domain\Dashboard\DashboardRepository;

class DatabaseDashboardRepository extends Database implements DashboardRepository
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
