<?php
declare(strict_types=1);

namespace App\Domain\Statistic;

use App\Domain\DomainException\DomainRecordNotFoundException;

class StatisticInvalidTableException extends DomainRecordNotFoundException
{
    public $message = 'The statistic table you requested data from is invalid.';
}
