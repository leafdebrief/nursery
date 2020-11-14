<?php
declare(strict_types=1);

namespace App\Domain\Spectrum;

use JsonSerializable;

class Spectrum implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $timestamp;

    /**
     * @var int
     */
    private $f1;

    /**
     * @var int
     */
    private $f2;

    /**
     * @var int
     */
    private $f3;

    /**
     * @var int
     */
    private $f4;

    /**
     * @var int
     */
    private $f5;

    /**
     * @var int
     */
    private $f6;

    /**
     * @var int
     */
    private $f7;

    /**
     * @var int
     */
    private $f8;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @return int
     */
    public function getF1(): int
    {
        return $this->f1;
    }

    /**
     * @return int
     */
    public function getF2(): int
    {
        return $this->f2;
    }

    /**
     * @return int
     */
    public function getF3(): int
    {
        return $this->f3;
    }

    /**
     * @return int
     */
    public function getF4(): int
    {
        return $this->f4;
    }

    /**
     * @return int
     */
    public function getF5(): int
    {
        return $this->f5;
    }

    /**
     * @return int
     */
    public function getF6(): int
    {
        return $this->f6;
    }

    /**
     * @return int
     */
    public function getF7(): int
    {
        return $this->f7;
    }

    /**
     * @return int
     */
    public function getF8(): int
    {
        return $this->f8;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => (int) $this->id,
            'timestamp' => $this->timestamp,
            'value' => [(int)$this->f1, (int)$this->f2, (int)$this->f3, (int)$this->f4, (int)$this->f5, (int)$this->f6, (int)$this->f7, (int)$this->f8]
        ];
    }
}
