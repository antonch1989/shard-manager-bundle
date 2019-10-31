<?php

namespace Cogitoweb\ShardManagerBundle\Repository;

use Coditoweb\ShardManagerBundle\Entity\CompanyInterface;

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
