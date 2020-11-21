<?php
declare(strict_types=1);

namespace App\Domain\Snapshot;

interface SnapshotRepository
{
    /**
     * @return Snapshot
     */
    public function getSnapshot(): Snapshot;
}
