<?php
declare(strict_types=1);

namespace App\Application\Actions\Spectrum;

use Psr\Http\Message\ResponseInterface as Response;

class ListSpectraAction extends SpectrumAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $spectra = $this->spectralRepository->findAll();

        $this->logger->info("Spectral list was viewed.");

        return $this->respondWithData($spectra);
    }
}
