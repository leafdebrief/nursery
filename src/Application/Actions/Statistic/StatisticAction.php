<?php
declare(strict_types=1);

namespace App\Application\Actions\Statistic;

use App\Application\Actions\Action;
use App\Domain\Statistic\StatisticRepository;
use Psr\Log\LoggerInterface;

abstract class StatisticAction extends Action
{
    /**
     * @var StatisticRepository
     */
    protected $statisticRepository;

    /**
     * @param LoggerInterface $logger
     * @param StatisticRepository  $statisticRepository
     */
    public function __construct(LoggerInterface $logger, StatisticRepository $statisticRepository)
    {
        parent::__construct($logger);
        $this->statisticRepository = $statisticRepository;
    }
}
