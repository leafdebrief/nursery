<?php
declare(strict_types=1);

namespace App\Infrastructure\Transients\Snapshot;

use App\Domain\Snapshot\Snapshot;
use App\Domain\Snapshot\SnapshotUnavailableException;
use App\Domain\Snapshot\SnapshotRepository;

class TransientSnapshotRepository implements SnapshotRepository
{
    /**
     * {@inheritdoc}
     */
    public function getSnapshot(): Snapshot
    {
        try {
          $command = escapeshellcmd('/usr/bin/env python3 /home/pi/nursery/scripts/readsensors.py');
          $output = shell_exec($command);
          return new Snapshot(json_decode($output));
        } catch (\Throwable $th) {
          throw new SnapshotUnavailableException();
        }
    }
}
