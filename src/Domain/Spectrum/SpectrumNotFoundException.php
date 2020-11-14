<?php
declare(strict_types=1);

namespace App\Domain\Spectrum;

use App\Domain\DomainException\DomainRecordNotFoundException;

class SpectrumNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The spectrum you requested does not exist.';
}
