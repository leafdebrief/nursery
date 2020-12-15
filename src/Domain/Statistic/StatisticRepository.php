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
     * @throws StatisticInvalidTableException
     * @throws StatisticNotFoundException
     */
    public function findStatisticOfId(string $table, int $id): Statistic;

    /**
     * @param string $table
     * @param string $range
     * @return Statistic
     * @throws StatisticInvalidTableException
     * @throws StatisticInvalidRangeException
     */
    public function getAverageFor(string $table, string $range): float;
}
