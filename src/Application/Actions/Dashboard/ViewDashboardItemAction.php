<?php
declare(strict_types=1);

namespace App\Application\Actions\Dashboard;

use Psr\Http\Message\ResponseInterface as Response;

class ViewDashboardItemAction extends DashboardAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $dashboardItemId = (int) $this->resolveArg('id');
        $dashboard = $this->dashboardRepository->findDashboardItemOfId($dashboardItemId);

        $this->logger->info("Dashboard item of id `${dashboardItemId}` was viewed.");

        return $this->respondWithData($dashboard);
    }
}
