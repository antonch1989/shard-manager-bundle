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

    /** @var string */
    private $hostName;

    public function __construct(CompanyRepositoryInterface $companyRepository, string $rootDir, string $hostName)
    {
        parent::__construct();
        $this->companyRepository = $companyRepository;
        $this->publicDirectory   = $rootDir.'/../public/';
        $this->hostName          = $hostName;
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

        switch ($outputFormat) {
            case 'txt':
                $filename = 'shards.txt';
                $filesystem->dumpFile($this->publicDirectory.$filename, $this->companyRepository->findTxtFileData());
                $output->writeln("$this->hostName/$filename");
                break;
            case 'csv':
                $filename = 'shards.csv';
                $filesystem->dumpFile($this->publicDirectory.$filename, $this->companyRepository->findCsvFileData());
                $output->writeln("$this->hostName/$filename");
                break;
            default:
                $output->writeln('Unknown format. Please specify a correct output format.');
        }
    }
}
