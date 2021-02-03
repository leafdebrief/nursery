<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Module;

use App\Infrastructure\Persistence\Database;
use App\Domain\Module\Module;
use App\Domain\Module\ModuleNotFoundException;
use App\Domain\Module\ModuleRepository;

class DatabaseModuleRepository extends Database implements ModuleRepository
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
      $stmt = $this->pdo->prepare('SELECT * FROM modules');
      $stmt->execute();
      $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Domain\Module\Module');
      return $stmt->fetchAll();
    }

    /**
     * {@inheritdoc}
     */
    public function findModuleOfId(int $id): Module
    {
        $stmt = $this->pdo->prepare('SELECT * FROM modules WHERE id = ?');
        $stmt->execute([$id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Domain\Module\Module');
        $sensor = $stmt->fetch();
        if (!$sensor) {
          throw new ModuleNotFoundException();
        }
        return $sensor;
    }
}
