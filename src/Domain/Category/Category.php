<?php
declare(strict_types=1);

namespace App\Domain\Category;

use JsonSerializable;

class Category implements JsonSerializable
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
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $unit;

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
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => (int)$this->id,
            'name' => $this->name,
            'label' => $this->label,
            'unit' => $this->unit
        ];
    }
}
