<?php
declare(strict_types=1);

namespace App\Application\Actions\Spectrum;

use App\Application\Actions\Action;
use App\Domain\Spectrum\SpectralRepository;
use Psr\Log\LoggerInterface;

abstract class SpectrumAction extends Action
{
    /**
     * @var SpectralRepository
     */
    protected $spectralRepository;

    /**
     * @param LoggerInterface $logger
     * @param SpectralRepository  $spectralRepository
     */
    public function __construct(LoggerInterface $logger, SpectralRepository $spectralRepository)
    {
        parent::__construct($logger);
        $this->spectralRepository = $spectralRepository;
    }
}
