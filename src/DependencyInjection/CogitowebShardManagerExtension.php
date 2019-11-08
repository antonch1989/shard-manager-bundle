<?php

namespace Cogitoweb\ShardManagerBundle\DependencyInjection;

use Cogitoweb\ShardManagerBundle\Entity\CompanyInterface;
use Cogitoweb\ShardManagerBundle\Repository\CompanyRepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
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
//        /** @var CompanyRepositoryInterface $companyRepository */
//        $companyRepository = $container->get('cogitoweb.repository.company');
//        $shards = $this->createShardsConfig($companyRepository->findOrderedById());

        $definition = new Definition(Connection::class);
//        $definition->setFactory([DriverManager::class, 'getConnection']);
//        $definition->setArgument(0, [
//            'wrapperClass' => 'Doctrine\DBAL\Sharding\PoolingShardConnection',
//            'driver' => 'pdo_mysql',
//            'shards' => $shards,
//            'shardChoser' => 'Doctrine\DBAL\Sharding\ShardChoser\MultiTenantShardChoser',
//        ]);
        $container->setDefinition('cogitoweb.multitenant_connection', $definition);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
