<?php
declare(strict_types=1);

namespace App\Application\Actions\Module;

use Psr\Http\Message\ResponseInterface as Response;

class ListModulesAction extends ModuleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $modules = $this->moduleRepository->findAll();

        $this->logger->info("Modules list was viewed.");

        return $this->respondWithData($modules);
    }
}
