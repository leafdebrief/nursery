<?php
declare(strict_types=1);

namespace App\Domain\Dashboard;

use App\Domain\DomainException\DomainRecordNotFoundException;

class DashboardItemNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The dashboard item you requested does not exist.';
}
