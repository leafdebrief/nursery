<?php
declare(strict_types=1);

namespace App\Application\Actions\Statistic;

use Psr\Http\Message\ResponseInterface as Response;

class ListStatisticsAction extends StatisticAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $table = $this->resolveArg('table');

        $statistics = $this->statisticRepository->findAll($table);

        $this->logger->info("Statistics list was viewed.");

        return $this->respondWithData($statistics);
    }
}
