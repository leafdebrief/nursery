<?php
declare(strict_types=1);

namespace App\Domain\Statistic;

use App\Domain\DomainException\DomainInvalidInputException;

class StatisticInvalidTableException extends DomainInvalidInputException
{
    public $message = 'The statistic table you requested data from is invalid.';
}
