<?php

namespace Cogitoweb\ShardManagerBundle\Entity;

/**
 * Interface CompanyInterface
 * @package Cogitoweb\ShardManagerBundle\Entity
 */
interface CompanyInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return ConfigurationInterface
     */
    public function getConfiguration(): ConfigurationInterface;
}
