<?php
declare(strict_types=1);

namespace App\Domain\Statistic;

interface StatisticRepository
{
    /**
     * @param string $table
     * @return Statistic[]
     */
    public function findAll(string $table): array;

    /**
     * @param string $table
     * @param int $id
     * @return Statistic
     * @throws StatisticNotFoundException
     */
    public function findStatisticOfId(string $table, int $id): Statistic;
}
