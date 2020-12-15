<?php
declare(strict_types=1);

namespace App\Domain\Statistic;

use App\Domain\DomainException\DomainInvalidInputException;

class StatisticInvalidRangeException extends DomainInvalidInputException
{
    public $message = 'The date range you requested data from is invalid.';
}
