<?php

namespace Cogitoweb\ShardManagerBundle\Entity;

/**
 * Interface ConfigurationInterface
 * @package Cogitoweb\ShardManagerBundle\Entity
 */
interface ConfigurationInterface
{
    /**
     * @return string the DSN to connect to database
     */
    public function getDatabaseConnection(): string;
}
