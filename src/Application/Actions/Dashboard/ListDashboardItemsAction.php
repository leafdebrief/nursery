<?php
declare(strict_types=1);

namespace App\Application\Actions\Dashboard;

use Psr\Http\Message\ResponseInterface as Response;

class ListDashboardItemsAction extends DashboardAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $dashboardItems = $this->dashboardRepository->findAll();

        $this->logger->info("Dashboard items list was viewed.");

        return $this->respondWithData($dashboardItems);
    }
}
