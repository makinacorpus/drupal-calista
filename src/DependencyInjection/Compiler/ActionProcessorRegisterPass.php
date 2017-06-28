<?php

namespace MakinaCorpus\Drupal\Calista\DependencyInjection\Compiler;

use MakinaCorpus\Drupal\Calista\Action\AbstractActionProcessor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers action processors into the action registry
 */
class ActionProcessorRegisterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('calista.processor_registry')) {
            return;
        }
        $definition = $container->getDefinition('calista.processor_registry');

        // Register automatic action provider based on action processors
        $taggedServices = $container->findTaggedServiceIds('calista.action');
        foreach ($taggedServices as $id => $attributes) {
            $def = $container->getDefinition($id);

            $class = $container->getParameterBag()->resolveValue($def->getClass());
            $refClass = new \ReflectionClass($class);

            if (!$refClass->isSubclassOf(AbstractActionProcessor::class)) {
                throw new \InvalidArgumentException(sprintf('Service "%s" must implement extend "%s".', $id, AbstractActionProcessor::class));
            }

            $definition->addMethodCall('register', [new Reference($id)]);
        }
    }
}
