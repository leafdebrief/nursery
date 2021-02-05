<?php
declare(strict_types=1);

namespace App\Domain\Category;

interface CategoryRepository
{
    /**
     * @return Category[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Category
     * @throws CategoryNotFoundException
     */
    public function findCategoryOfId(int $id): Category;
}
