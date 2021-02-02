<?php
declare(strict_types=1);

namespace App\Domain\Module;

use JsonSerializable;

class Module implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $category_id;

    /**
     * @var int
     */
    private $sensor_id;

    /**
     * @var string|null
     */
    private $sensor_address;

    /**
     * @var int|null
     */
    private $sensor_channel;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * @return int
     */
    public function getSensorId(): int
    {
        return $this->sensor_id;
    }

    /**
     * @return string
     */
    public function getSensorAddress(): string
    {
        return $this->sensor_address;
    }

    /**
     * @return int
     */
    public function getSensorChannel(): int
    {
        return $this->sensor_channel;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'sensor_id' => $this->sensor_id,
            'sensor_address' => $this->sensor_address,
            'sensor_channel' => $this->sensor_channel
        ];
    }
}
