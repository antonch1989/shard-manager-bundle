<?php

namespace Coditoweb\ShardManagerBundle\Entity;

interface ConfigurationInterface
{
    /**
     * @return string the DSN to connect to database
     */
    public function getDatabaseConnection(): string;
}
