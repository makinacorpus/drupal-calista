<?php

namespace MakinaCorpus\Drupal\Calista\Portlet;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use MakinaCorpus\Calista\Action\Action;
use MakinaCorpus\Calista\Action\ActionProviderInterface;
use MakinaCorpus\Calista\Datasource\InputDefinition;
use MakinaCorpus\Calista\Datasource\Query;
use MakinaCorpus\Calista\DependencyInjection\ViewFactory;
use MakinaCorpus\Calista\View\ViewDefinition;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Degraded, temporary and minimalistic implementation for portlets.
 */
abstract class AbstractPortlet
{
    use StringTranslationTrait;

    protected $actionProvider;
    protected $authorizationChecker;
    protected $viewFactory;

    /**
     * Set authorization checker
     *
     * @param v
     */
    public function setActionProvider(ActionProviderInterface $actionProvider)
    {
        $this->actionProvider = $actionProvider;
    }

    /**
     * Set authorization checker
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Set view factory
     *
     * @param ViewFactory $viewFactory
     */
    public function setViewFactory(ViewFactory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * Render page
     *
     * @param string $datasourceId
     * @param string $template
     * @param array $baseQuery
     * @param string $sortField
     * @param string $sortOrder
     * @param int $limit
     *
     * @return string
     */
    protected function renderPage($datasourceId, $template, array $baseQuery = [], $sortField = null, $sortOrder = Query::SORT_DESC, $limit = 10)
    {
        $datasource = $this->viewFactory->getDatasource('ucms_contrib.datasource.node');

        $inputDefinition = new InputDefinition($datasource, [
            'base_query'          => $baseQuery,
            'limit_default'       => $limit,
            'pager_enable'        => false,
            'search_enable'       => false,
            'sort_default_field'  => $sortField ? $sortField : '',
            'sort_default_order'  => $sortOrder,
        ]);
        $viewDefinition = new ViewDefinition([
            'enabled_filters'   => [],
            'properties'        => [],
            'show_filters'      => false,
            'show_pager'        => false,
            'show_search'       => false,
            'show_sort'         => false,
            'templates'         => ['default' => $template],
            'view_type'         => 'twig_page',
        ]);
        $query = $inputDefinition->createQueryFromArray([]);
        $items = $datasource->getItems($query);

        return $this->viewFactory->getView('twig_page')->render($viewDefinition, $items, $query);
    }

    /**
     * Get title
     *
     * @return string
     */
    abstract public function getTitle();

    /**
     * Render the portlet
     *
     * @return string
     */
    abstract public function getContent();

    /**
     * Get route for title linke
     *
     * return route
     */
    public function getRoute()
    {
        return '';
    }

    /**
     * Get actions
     *
     * @return Action[]
     */
    public function getActions()
    {
        return [];
    }

    /**
     * Can current user see this portlet
     *
     * @return bool
     */
    public function isGranted()
    {
        return true;
    }
}
