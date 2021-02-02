<?php
declare(strict_types=1);

namespace App\Domain\Module;

interface ModuleRepository
{
    /**
     * @return Module[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Module
     * @throws ModuleNotFoundException
     */
    public function findModuleOfId(int $id): Module;
}
