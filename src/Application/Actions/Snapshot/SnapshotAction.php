<?php
declare(strict_types=1);

namespace App\Application\Actions\Snapshot;

use App\Application\Actions\Action;
use App\Domain\Snapshot\SnapshotRepository;
use Psr\Log\LoggerInterface;

abstract class SnapshotAction extends Action
{
    /**
     * @var SnapshotRepository
     */
    protected $snapshotRepository;

    /**
     * @param LoggerInterface $logger
     * @param SnapshotRepository  $snapshotRepository
     */
    public function __construct(LoggerInterface $logger, SnapshotRepository $snapshotRepository)
    {
        parent::__construct($logger);
        $this->snapshotRepository = $snapshotRepository;
    }
}
