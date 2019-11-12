<?php

namespace Cogitoweb\ShardManagerBundle\Command;

use Cogitoweb\ShardManagerBundle\Entity\CompanyInterface;
use Cogitoweb\ShardManagerBundle\Repository\CompanyRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ShardsListCommand
 * @package Cogitoweb\ShardManagerBundle\Command
 */
class ShardsListCommand extends Command
{
    /** @var CompanyRepositoryInterface */
    private $companyRepository;

    /**
     * ShardsListCommand constructor.
     * @param CompanyRepositoryInterface $companyRepository
     */
    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        parent::__construct();
        $this->companyRepository = $companyRepository;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('cogitoweb:shards:list')
            ->addOption('format', 'f', InputOption::VALUE_REQUIRED, 'Output file format', 'txt')
            ->setDescription('Outputs a list of shards in a specified format.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputFormat = $input->getOption('format');

        switch ($outputFormat) {
            case 'txt':
                $table = new Table($output);
                $table->setHeaders(['Company name', 'Shard ID']);
                $companies = $this->companyRepository->findOrderedByName();
                $table->setRows($this->createTableRows($companies));
                $table->render();
                break;
            case 'csv':
                $companies = $this->companyRepository->findOrderedById();
                $csvData = $this->createCsvData($companies);
                $output->writeln($csvData);
                break;
            default:
                $output->writeln('Unknown format. Please specify a correct output format.');
        }
    }

    /**
     * Creates table rows from an array of companies
     * @param CompanyInterface[] $companies
     * @return array
     */
    private function createTableRows(array $companies): array
    {
        $rows = [];

        foreach ($companies as $company) {
            $rows[] = [$company->getName(), $company->getShardNumber()];
        }

        return $rows;
    }

    /**
     * Creates csv data from an array of companies
     * @param CompanyInterface[] $companies
     * @return string
     */
    private function createCsvData(array $companies): string
    {
        $result = '';
        foreach ($companies as $company) {
            $result .= $company->getShardNumber().',';
        }
        $result = \rtrim($result, ',');

        return $result;
    }
}
