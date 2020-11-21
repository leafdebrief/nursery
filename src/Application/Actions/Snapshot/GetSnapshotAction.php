<?php
declare(strict_types=1);

namespace App\Application\Actions\Snapshot;

use Psr\Http\Message\ResponseInterface as Response;

class GetSnapshotAction extends SnapshotAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $snapshot = $this->snapshotRepository->getSnapshot();
        $timestamp = $snapshot->timestamp;

        $this->logger->info("Snapshot with timestamp `${timestamp}` was viewed.");

        return $this->respondWithData($snapshot);
    }
}
