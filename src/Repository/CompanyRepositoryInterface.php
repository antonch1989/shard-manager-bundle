<?php

namespace Cogitoweb\ShardManagerBundle\Repository;

use Cogitoweb\ShardManagerBundle\Entity\CompanyInterface;

/**
 * Interface CompanyRepositoryInterface
 * @package Cogitoweb\ShardManagerBundle\Repository
 */
interface CompanyRepositoryInterface
{
    /**
     * @return CompanyInterface[]
     */
    public function findOrderedById(): array;

    /**
     * @return CompanyInterface[]
     */
    public function findOrderedByName(): array;
}
