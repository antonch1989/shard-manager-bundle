<?php

namespace Cogitoweb\ShardManagerBundle\Command;

use Cogitoweb\ShardManagerBundle\Repository\CompanyRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ShardsListCommand extends Command
{
    /** @var CompanyRepositoryInterface */
    private $companyRepository;

    /** @var string */
    private $publicDirectory;

    public function __construct(CompanyRepositoryInterface $companyRepository, string $rootDir)
    {
        parent::__construct();
        $this->companyRepository = $companyRepository;
        $this->publicDirectory   = $rootDir.'../public/';
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
        $filesystem   = new Filesystem();
        $outputFormat = $input->getOption('format');

        if ($outputFormat === 'txt') {
            $filesystem->dumpFile($this->publicDirectory.'shards.txt', 'abc');
        } elseif ($outputFormat === 'csv') {
            $filesystem->dumpFile($this->publicDirectory.'shards.csv', 'def');
        } else {
            $output->writeln('Unknown format. Please specify a correct output format.');
        }
    }
}
