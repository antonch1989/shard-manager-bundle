<?php

namespace Cogitoweb\ShardManagerBundle;

use Cogitoweb\ShardManagerBundle\Entity\CompanyInterface;
use Cogitoweb\ShardManagerBundle\Repository\CompanyRepositoryInterface;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class CogitowebShardManagerBundle
 * @package Cogitoweb\ShardManagerBundle
 */
class CogitowebShardManagerBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function boot()
    {
        parent::boot();
        /** @var CompanyRepositoryInterface $companyRepository */
        $companyRepository = $this->container->get('cogitoweb.repository.company');
        $shards = $this->createShardsConfig($companyRepository->findOrderedById());
        $this->container->set('cogitoweb.multitenant_connection', DriverManager::getConnection([
            'wrapperClass' => 'Doctrine\DBAL\Sharding\PoolingShardConnection',
            'driver' => 'pdo_mysql',
            'global' => ['user' => '', 'password' => '', 'host' => '', 'dbname' => ''],
            'shards' => $shards,
            'shardChoser' => 'Doctrine\DBAL\Sharding\ShardChoser\MultiTenantShardChoser',
        ]));
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
