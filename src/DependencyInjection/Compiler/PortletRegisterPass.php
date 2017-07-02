<?php

namespace MakinaCorpus\Drupal\Calista\DependencyInjection\Compiler;

use MakinaCorpus\Drupal\Calista\Portlet\AbstractPortlet;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers portlets
 */
class PortletRegisterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('calista.portlet_registry')) {
            return;
        }
        $definition = $container->getDefinition('calista.portlet_registry');
        $services = [];

        // Register automatic action provider based on action processors
        $taggedServices = $container->findTaggedServiceIds('calista.portlet');
        foreach ($taggedServices as $id => $attributes) {
            $def = $container->getDefinition($id);

            $class = $container->getParameterBag()->resolveValue($def->getClass());
            $refClass = new \ReflectionClass($class);

            if (!$refClass->isSubclassOf(AbstractPortlet::class)) {
                throw new \InvalidArgumentException(sprintf('Service "%s" must implement extend "%s".', $id, AbstractPortlet::class));
            }

            $def->setPublic(false);
            $def->addMethodCall('setActionProvider', [new Reference('calista.action_provider_registry')]);
            $def->addMethodCall('setAuthorizationChecker', [new Reference('security.authorization_checker')]);
            $def->addMethodCall('setViewFactory', [new Reference('calista.view_factory')]);

            $services[] = new Reference($id);
        }

        if ($services) {
            $definition->setArguments([$services]);
        }
    }
}
