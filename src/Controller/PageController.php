<?php

namespace MakinaCorpus\Drupal\Calista\Controller;

use MakinaCorpus\Calista\Controller\PageControllerTrait;
use MakinaCorpus\Drupal\Sf\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Concrete page controller implementation
 *
 * It will be used automatically for generated routes.
 */
class PageController extends Controller
{
    use PageControllerTrait;

    /**
     * Render page
     */
    public function pageAction(Request $request, $name)
    {
        return $this->renderPageResponse($name, $request);
    }
}
