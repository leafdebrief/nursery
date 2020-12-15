<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Spectrum;

use App\Domain\Spectrum\Spectrum;
use App\Domain\Spectrum\SpectrumNotFoundException;
use App\Domain\Spectrum\SpectralRepository;

class DatabaseSpectralRepository implements SpectralRepository
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * InMemoryUserRepository constructor.
     *
     * @param array|null $users
     */
    public function __construct()
    {
        
        $dsn = "mysql:host=localhost;dbname=nursery;charset=utf8mb4";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new \PDO($dsn, 'root', 'kl99l9jk', $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
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
