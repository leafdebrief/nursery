<?php
declare(strict_types=1);

namespace App\Domain\Statistic;

use JsonSerializable;

class Statistic implements JsonSerializable
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
     * @var string
     */
    private $value;

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
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => (int) $this->id,
            'timestamp' => $this->timestamp,
            'value' => (float) $this->value
        ];
    }
}
