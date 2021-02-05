<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Category;

use App\Infrastructure\Persistence\Database;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryNotFoundException;
use App\Domain\Category\CategoryRepository;

class DatabaseCategoryRepository extends Database implements CategoryRepository
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
      $stmt = $this->pdo->prepare('SELECT * FROM categories');
      $stmt->execute();
      $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Domain\Category\Category');
      return $stmt->fetchAll();
    }

    /**
     * {@inheritdoc}
     */
    public function findCategoryOfId(int $id): Category
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Domain\Category\Category');
        $category = $stmt->fetch();
        if (!$category) {
          throw new CategoryNotFoundException();
        }
        return $category;
    }
}
