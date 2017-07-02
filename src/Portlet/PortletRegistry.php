<?php

namespace MakinaCorpus\Drupal\Calista\Portlet;

/**
 * Portlet registry
 */
class PortletRegistry
{
    private $portlets = [];

    /**
     * Default constructor
     *
     * @param AbstractPortlet[]
     */
    public function __construct(array $portlets = [])
    {
        $this->portlets = $portlets;
    }

    /**
     * Get portlets for current user
     *
     * @return AbstractPortlet[]
     */
    public function getPortletsForCurrentUser()
    {
        return array_filter($this->portlets, function ($portlet) {
            return $portlet instanceof AbstractPortlet && $portlet->isGranted();
        });
    }
}
