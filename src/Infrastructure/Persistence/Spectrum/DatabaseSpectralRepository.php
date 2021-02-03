<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Spectrum;

use App\Infrastructure\Persistence\Database;
use App\Domain\Spectrum\Spectrum;
use App\Domain\Spectrum\SpectrumNotFoundException;
use App\Domain\Spectrum\SpectralRepository;

class DatabaseSpectralRepository extends Database implements SpectralRepository
{
  public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
      $stmt = $this->pdo->prepare('SELECT * FROM spectral');
      $stmt->execute();
      $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Domain\Spectrum\Spectrum');
      return $stmt->fetchAll();
    }

    /**
     * {@inheritdoc}
     */
    public function findSpectrumOfId(int $id): Spectrum
    {
        $stmt = $this->pdo->prepare('SELECT * FROM spectral WHERE id = ?');
        $stmt->execute([$id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Domain\Spectrum\Spectrum');
        $sensor = $stmt->fetch();
        if (!$sensor) {
          throw new SpectrumNotFoundException();
        }
        return $sensor;
    }
}
