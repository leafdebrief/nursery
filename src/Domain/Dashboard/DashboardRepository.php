<?php
declare(strict_types=1);

namespace App\Domain\Dashboard;

interface DashboardRepository
{
    /**
     * @return DashboardItem[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return DashboardItem
     * @throws DashboardItemNotFoundException
     */
    public function findDashboardItemOfId(int $id): DashboardItem;
}
