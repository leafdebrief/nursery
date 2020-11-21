<?php
declare(strict_types=1);

namespace App\Domain\Snapshot;

use App\Domain\DomainException\DomainHardwareException;

class SnapshotUnavailableException extends DomainHardwareException
{
    public $message = 'The snapshot could not be made because the sensors are unavailable.';
}
