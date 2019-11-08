<?php

namespace Cogitoweb\ShardManagerBundle;

use Cogitoweb\ShardManagerBundle\Entity\CompanyInterface;
use Cogitoweb\ShardManagerBundle\Repository\CompanyRepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class CogitowebShardManagerBundle
 * @package Cogitoweb\ShardManagerBundle
 */
class CogitowebShardManagerBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var CompanyRepositoryInterface $companyRepository */
        $companyRepository = $container->get('cogitoweb.repository.company');
        $shards = $this->createShardsConfig($companyRepository->findOrderedById());

        $definition = new Definition(Connection::class);
        $definition->setFactory([DriverManager::class, 'getConnection']);
        $definition->setArgument(0, [
            'wrapperClass' => 'Doctrine\DBAL\Sharding\PoolingShardConnection',
            'driver' => 'pdo_mysql',
            'shards' => $shards,
            'shardChoser' => 'Doctrine\DBAL\Sharding\ShardChoser\MultiTenantShardChoser',
        ]);
        $container->setDefinition('cogitoweb.multitenant_connection', $definition);
    }

    /**
     * @param CompanyInterface[] $companies
     * @return array
     */
    private function createShardsConfig(array $companies): array
    {
        $shards = [];

        foreach ($companies as $company) {
            $shards[] = ['id' => $company->getId(), 'url' => $company->getConfiguration()->getDatabaseConnection()];
        }

        return $shards;
    }
}
