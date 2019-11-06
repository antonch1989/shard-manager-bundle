<?php

namespace Cogitoweb\ShardManagerBundle\DependencyInjection;

use Doctrine\DBAL\DriverManager;
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
        $connection = DriverManager::getConnection(array(
            'wrapperClass' => 'Doctrine\DBAL\Sharding\PoolingShardConnection',
            'driver' => 'pdo_mysql',
            'global' => array('user' => '', 'password' => '', 'host' => '', 'dbname' => ''),
            'shards' => array(
              array('id' => 1, 'user' => 'slave1', 'password', 'host' => '', 'dbname' => ''),
              array('id' => 2, 'user' => 'slave2', 'password', 'host' => '', 'dbname' => ''),
            ),
            'shardChoser' => 'Doctrine\DBAL\Sharding\ShardChoser\MultiTenantShardChoser',
        ));
        $container->set('cogitoweb.multitenant_connection', $connection);
        
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
