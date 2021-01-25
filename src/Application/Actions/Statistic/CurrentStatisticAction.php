<?php
declare(strict_types=1);

namespace App\Application\Actions\Statistic;

use Psr\Http\Message\ResponseInterface as Response;

class CurrentStatisticAction extends StatisticAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $table = $this->resolveArg('table');

        $statistic = $this->statisticRepository->getCurrentStatistic($table);

        $this->logger->info("Current statistic of for `${table}` was viewed.");

        return $this->respondWithData($statistic);
    }
}
