<?php
declare(strict_types=1);

namespace App\Domain\Sensor;

use JsonSerializable;

class Sensor implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $library;

    /**
     * @var string
     */
    private $label;

    /**
     * @var boolean
     */
    private $seesaw;

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
    public function getLibrary(): string
    {
        return $this->library;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return boolean
     */
    public function getSeesaw(): bool
    {
        return $this->seesaw;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => (int)$this->id,
            'library' => $this->library,
            'label' => $this->label,
            'seesaw' => (bool)$this->seesaw
        ];
    }
}
