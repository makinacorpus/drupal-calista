<?php

namespace Drupal\Module\calista;

use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use MakinaCorpus\Calista\DependencyInjection\Compiler\ActionProviderRegisterPass;
use MakinaCorpus\Calista\DependencyInjection\Compiler\DowngradeCompatibilityPass;
use MakinaCorpus\Calista\DependencyInjection\Compiler\PageDefinitionRegisterPass;
use MakinaCorpus\Drupal\Calista\DependencyInjection\Compiler\ActionProcessorRegisterPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Implements Drupal 8 service provider.
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/Resources/config'));
        $loader->load('core.yml');
        $loader->load('drupal.yml');

        $container->addCompilerPass(new DowngradeCompatibilityPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 50 /* Make it run before twig's one */);
        $container->addCompilerPass(new ActionProviderRegisterPass());
        $container->addCompilerPass(new ActionProcessorRegisterPass());
        $container->addCompilerPass(new PageDefinitionRegisterPass(), PassConfig::TYPE_BEFORE_REMOVING);
    }
}
