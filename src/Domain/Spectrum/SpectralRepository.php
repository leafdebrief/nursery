<?php
declare(strict_types=1);

namespace App\Domain\Spectrum;

interface SpectralRepository
{
    /**
     * @return Spectrum[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Spectrum
     * @throws SpectrumNotFoundException
     */
    public function findSpectrumOfId(int $id): Spectrum;
}
