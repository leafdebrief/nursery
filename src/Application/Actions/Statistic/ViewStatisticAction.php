<?php
declare(strict_types=1);

namespace App\Application\Actions\Statistic;

use Psr\Http\Message\ResponseInterface as Response;

class ViewStatisticAction extends StatisticAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $table = $this->resolveArg('table');
        $statisticId = (int) $this->resolveArg('id');

        $statistic = $this->statisticRepository->findStatisticOfId($table, $statisticId);

        $this->logger->info("Statistic of id `${statisticId}` was viewed.");

        return $this->respondWithData($statistic);
    }
}
