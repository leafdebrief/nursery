<?php
declare(strict_types=1);

namespace App\Application\Actions\Module;

use App\Application\Actions\Action;
use App\Domain\Module\ModuleRepository;
use Psr\Log\LoggerInterface;

abstract class ModuleAction extends Action
{
    /**
     * @var ModuleRepository
     */
    protected $moduleRepository;

    /**
     * @param LoggerInterface $logger
     * @param ModuleRepository  $moduleRepository
     */
    public function __construct(LoggerInterface $logger, ModuleRepository $moduleRepository)
    {
        parent::__construct($logger);
        $this->moduleRepository = $moduleRepository;
    }
}
