<?php
declare(strict_types=1);

namespace App\Application\Actions\Spectrum;

use Psr\Http\Message\ResponseInterface as Response;

class ViewSpectrumAction extends SpectrumAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $sensorId = (int) $this->resolveArg('id');
        $sensor = $this->spectralRepository->findSpectrumOfId($sensorId);

        $this->logger->info("Spectrum of id `${sensorId}` was viewed.");

        return $this->respondWithData($sensor);
    }
}
