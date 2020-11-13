<?php
declare(strict_types=1);

namespace App\Domain\Statistic;

use App\Domain\DomainException\DomainRecordNotFoundException;

class StatisticNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The statistic you requested does not exist.';
}
