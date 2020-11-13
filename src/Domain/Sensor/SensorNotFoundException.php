<?php
declare(strict_types=1);

namespace App\Domain\Sensor;

use App\Domain\DomainException\DomainRecordNotFoundException;

class SensorNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The sensor you requested does not exist.';
}
