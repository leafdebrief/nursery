<?php
declare(strict_types=1);

namespace App\Application\Actions\Module;

use Psr\Http\Message\ResponseInterface as Response;

class ViewModuleAction extends ModuleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $moduleId = (int) $this->resolveArg('id');
        $module = $this->moduleRepository->findModuleOfId($moduleId);

        $this->logger->info("Module of id `${moduleId}` was viewed.");

        return $this->respondWithData($module);
    }
}
