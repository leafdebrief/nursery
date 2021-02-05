<?php
declare(strict_types=1);

namespace App\Application\Actions\Category;

use Psr\Http\Message\ResponseInterface as Response;

class ListCategoriesAction extends CategoryAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $categories = $this->categoryRepository->findAll();

        $this->logger->info("Categories list was viewed.");

        return $this->respondWithData($categories);
    }
}
