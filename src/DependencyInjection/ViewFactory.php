<?php

namespace MakinaCorpus\Dashboard\DependencyInjection;

use MakinaCorpus\Dashboard\View\ViewInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * God I hate to register more factories to the DIC, but we have some
 * dependencies that we should inject into pages, and only this allows
 * us to do it properly
 */
final class ViewFactory
{
    private $container;
    private $pageClasses = [];
    private $pageServices = [];
    private $viewClasses = [];
    private $viewServices = [];

    /**
     * Default constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Register page types
     *
     * @param string[] $services
     *   Keys are names, values are service identifiers
     */
    public function registerPageDefinitions(array $services, array $classes = [])
    {
        $this->pageServices = $services;
        $this->pageClasses = $classes;
    }

    /**
     * Register page types
     *
     * @param string[] $services
     *   Keys are names, values are service identifiers
     * @param string[] $classes
     *   Keys are class names, values are service identifiers
     */
    public function registerViews(array $services, array $classes = [])
    {
        $this->viewServices = $services;
        $this->viewClasses = $classes;
    }

    /**
     * Create instance
     *
     * @param string $class
     * @param string $name
     * @param string[] $services
     * @param string[] $classes
     *
     * @return object
     */
    private function createInstance($class, $name, array $services, array $classes)
    {
        if (isset($classes[$name])) {
            return $this->createInstance($class, $classes[$name], $services, $classes);
        }

        if (isset($services[$name])) {
            $id = $services[$name];
        } else {
            $id = $name;
        }

        try {
            $instance = $this->container->get($id);

            if (!is_a($instance, $class)) {
                throw new \InvalidArgumentException(sprintf("service '%s' with id '%s' does not implement %s", $name, $id, $class));
            }
        } catch (ServiceNotFoundException $e) {

            if (class_exists($name)) {
                $instance = new $name();

                if (!is_a($instance, $class)) {
                    throw new \InvalidArgumentException(sprintf("class '%s' does not implement %s", $name, $class));
                }
            } else {
                throw new \InvalidArgumentException(sprintf("service '%s' service id '%s' does not exist in container or class does not exists", $name, $id));
            }
        }

        if ($instance instanceof ServiceInterface) {
            $instance->setId($id);
        }
        if ($instance instanceof ContainerAwareInterface) {
            $instance->setContainer($this->container);
        }

        return $instance;
    }

    /**
     * Get page definition
     *
     * @param string $name
     *
     * @return PageDefinitionInterface
     */
    public function getPageDefinition($name)
    {
        return $this->createInstance(PageDefinitionInterface::class, $name, $this->pageServices, $this->pageClasses);
    }

    /**
     * Get view
     *
     * @param string $name
     *
     * @return ViewInterface
     */
    public function getView($name)
    {
        return $this->createInstance(ViewInterface::class, $name, $this->viewServices, $this->viewClasses);
    }
}