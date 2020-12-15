<?php
declare(strict_types=1);

namespace App\Application\Actions\Statistic;

use Psr\Http\Message\ResponseInterface as Response;

class AverageStatisticAction extends StatisticAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $table = $this->resolveArg('table');
        $range = $this->resolveArg('range');

        $average = $this->statisticRepository->getAverageFor($table, $range);

        $this->logger->info("Average for $range was viewed.");

        return $this->respondWithData($average);
    }
}
