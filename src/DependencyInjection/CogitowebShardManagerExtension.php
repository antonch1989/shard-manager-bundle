<?php

namespace Cogitoweb\ShardManagerBundle\DependencyInjection;

use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class CogitowebShardManagerExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $definition = new Definition(Connection::class);
        $definition->setPublic(true);
        $container->setDefinition('cogitoweb.multitenant_connection', $definition);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
