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
            'driver' => $this->container->getParameter('connection_driver'),
            'global' => [
                'user'     => $this->container->getParameter('user'), 
                'password' => $this->container->getParameter('password'),
                'host'     => $this->container->getParameter('host'),
                'dbname'   => $this->container->getParameter('dbname'),
            ],
            'shards'      => $shards,
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
            $shards[] = ['id' => $company->getShardNumber(), 'url' => $company->getConfiguration()->getDatabaseConnection()];
        }

        return $shards;
    }
}
