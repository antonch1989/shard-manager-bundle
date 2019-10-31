<?php

namespace Cogitoweb\ShardManagerBundle\Command;

use Cogitoweb\ShardManagerBundle\Repository\CompanyRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShardsListCommand extends Command
{
    /** @var CompanyRepositoryInterface */
    private $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        parent::__construct();
        $this->companyRepository = $companyRepository;
    }

    protected function configure()
    {
        $this
            ->setName('cogitoweb:shards:list')
            ->addOption('format', 'f', InputOption::VALUE_REQUIRED, 'Output file format', 'txt')
        ;
    }

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

    private function createTableRows(array $companies): array
    {
        $rows = [];

        foreach ($companies as $company) {
            $rows[] = [$company->getName(), $company->getId()->toString()];
        }

        return $rows;
    }

    private function createCsvData(array $companies): string
    {
        $result = '';
        foreach ($companies as $company) {
            $result .= $company->getId()->toString().',';
        }
        $result = \rtrim($result, ',');

        return $result;
    }
}
