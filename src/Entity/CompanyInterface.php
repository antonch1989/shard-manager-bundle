<?php

namespace Cogitoweb\ShardManagerBundle\Entity;

use Ramsey\Uuid\UuidInterface;

/**
 * Interface CompanyInterface
 * @package Cogitoweb\ShardManagerBundle\Entity
 */
interface CompanyInterface
{
    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int
     */
    public function getShardNumber(): int;

    /**
     * @return ConfigurationInterface
     */
    public function getConfiguration(): ConfigurationInterface;
}
