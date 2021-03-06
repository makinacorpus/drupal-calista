<?php

namespace Drupal\Module\calista;

use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use MakinaCorpus\Calista\CalistaBundle;
use MakinaCorpus\Drupal\Calista\DependencyInjection\Compiler\ActionProcessorRegisterPass;
use MakinaCorpus\Drupal\Calista\DependencyInjection\Compiler\PortletRegisterPass;
use Symfony\Component\Config\FileLocator;
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
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
        $loader->load('drupal.yml');
        $loader->load('pages.yml');

        $container->addCompilerPass(new ActionProcessorRegisterPass());
        $container->addCompilerPass(new PortletRegisterPass());
    }

    /**
     * {@inhertidoc}
     */
    public function registerBundles()
    {
        return [
            new CalistaBundle(),
        ];
    }
}
