<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Module;

use App\Domain\Module\Module;
use App\Domain\Module\ModuleNotFoundException;
use App\Domain\Module\ModuleRepository;

class DatabaseModuleRepository implements ModuleRepository
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
