<?php
declare(strict_types=1);

namespace App\Domain\Dashboard;

use JsonSerializable;

class DashboardItem implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var int
     */
    private $module_id;

    /**
     * @var int
     */
    private $track;

    /**
     * @var int
     */
    private $position;

    /**
     * @var int
     */
    private $rows;

    /**
     * @var int
     */
    private $cols;

    /**
     * @var boolean
     */
    private $hidden;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getModuleId(): int
    {
        return $this->module_id;
    }

    /**
     * @return int
     */
    public function getTrack(): int
    {
        return $this->track;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * @return int
     */
    public function getCols(): int
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function getHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => (int)$this->id,
            'module_id' => (int)$this->module_id,
            'track' => (int)$this->track,
            'position' => (int)$this->position,
            'rows' => (int)$this->rows,
            'cols' => (int)$this->cols,
            'hidden' => (bool)$this->hidden
        ];
    }
}
