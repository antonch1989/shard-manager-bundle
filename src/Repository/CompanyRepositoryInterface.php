<?php

namespace Cogitoweb\ShardManagerBundle\Repository;

interface CompanyRepositoryInterface
{
    public function findTxtFileData(): string;

    public function findCsvFileData(): string;
}
