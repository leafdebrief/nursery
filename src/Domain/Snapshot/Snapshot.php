<?php
declare(strict_types=1);

namespace App\Domain\Snapshot;

use JsonSerializable;

class Snapshot implements JsonSerializable
{
    /**
     * @var string
     */
    private $timestamp;

    /**
     * @var float
     */
    private $temperature;

    /**
     * @var float
     */
    private $humidity;

    /**
     * @var int[]
     */
    private $spectral;

    /**
     * @var int
     */
    private $moisture;

    /**
     * @param string $timestamp
     * @param float $temperature
     * @param float $humidity
     * @param int[] $spectral
     * @param int $moisture
     * @return void
     */
    function __construct(\StdClass $snapshot) {
      $this->timestamp = $snapshot->timestamp;
      $this->temperature = $snapshot->temperature;
      $this->humidity = $snapshot->humidity;
      $this->spectral = $snapshot->spectral;
      $this->moisture = $snapshot->moisture;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @return float
     */
    public function getTemperature(): float
    {
        return $this->temperature;
    }

    /**
     * @return float
     */
    public function getHumidity(): float
    {
        return $this->humidity;
    }

    /**
     * @return int[]
     */
    public function getSpectral(): array
    {
        return $this->spectral;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'timestamp' => $this->timestamp,
            'temperature' => $this->temperature,
            'humidity' => $this->humidity,
            'spectral' => $this->spectral,
            'moisture' => $this->moisture
        ];
    }
}
