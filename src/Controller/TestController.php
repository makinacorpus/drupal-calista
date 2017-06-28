<?php

namespace MakinaCorpus\Drupal\Calista\Controller;

use MakinaCorpus\Calista\Controller\PageControllerTrait;
use MakinaCorpus\Drupal\Calista\Page\AccountPageDefinition;
use MakinaCorpus\Drupal\Calista\Page\NodePageDefinition;
use MakinaCorpus\Drupal\Sf\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Action processor controller
 */
class TestController extends Controller
{
    use PageControllerTrait;

    /**
     * List all nodes
     */
    public function listNodesAction(Request $request)
    {
        return $this->renderPage(NodePageDefinition::class, $request);
    }

    /**
     * List all users
     */
    public function listAccountsAction(Request $request)
    {
        return $this->renderPage(AccountPageDefinition::class, $request);
    }
}
