<?php
declare(strict_types=1);

namespace App\Application\Actions\Dashboard;

use App\Application\Actions\Action;
use App\Domain\Dashboard\DashboardRepository;
use Psr\Log\LoggerInterface;

abstract class DashboardAction extends Action
{
    /**
     * @var DashboardRepository
     */
    protected $dashboardRepository;

    /**
     * @param LoggerInterface $logger
     * @param DashboardRepository  $dashboardRepository
     */
    public function __construct(LoggerInterface $logger, DashboardRepository $dashboardRepository)
    {
        parent::__construct($logger);
        $this->dashboardRepository = $dashboardRepository;
    }
}
