<?php

namespace Coditoweb\ShardManagerBundle\Entity;

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
     * @return ConfigurationInterface
     */
    public function getConfiguration(): ConfigurationInterface;
}
