<?php

namespace Cogitoweb\ShardManagerBundle\DependencyInjection;

use Doctrine\DBAL\Driver\PDOConnection;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class CogitowebShardManagerExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @param ContainerBuilder $container
     * @throws \ReflectionException
     */
    public function prepend(ContainerBuilder $container)
    {
        $doctrinConfig = $container->getExtensionConfig('doctrine');
        $pdo = $this->getPDOConnection();
        $pdoStatement = $pdo->query('
            SELECT company.id, configuration.dsn FROM company INNER JOIN configuration ON company.id = configuration.company_id
        ');
        $shardData = $pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
        $shards = [];
        foreach ($shardData as $data) {
            $shards[] = ['id' => (int) $data['id'], 'url' => $data['dsn']];
        }

        $doctrinConfig[0]['dbal']['connections']['multitenant'] = [
            "host"         => "%env(resolve:DATABASE_HOST)%",
            "port"         => "%env(resolve:DATABASE_PORT)%",
            "user"         => "%env(resolve:DATABASE_USER)%",
            "password"     => "%env(resolve:DATABASE_PASSWORD)%",
            "driver"       => "%env(resolve:DATABASE_DRIVER)%",
            'shards'       => $shards,
            'shard_choser' => 'Doctrine\DBAL\Sharding\ShardChoser\MultiTenantShardChoser',
        ];

        $property = new \ReflectionProperty($container,'extensionConfigs');
        $property->setAccessible(true);
        $bundleConfig = $property->getValue($container);
        $bundleConfig['doctrine'] = $doctrinConfig;
        $property->setValue($container, $bundleConfig);
    }

    /**
     * @return PDOConnection
     */
    private function getPDOConnection(): PDOConnection
    {
        $dsn = \sprintf(
            '%s:dbname=%s;host=%s',
            \str_replace('pdo_', '', $_ENV['DATABASE_DRIVER']), $_ENV['DATABASE_NAME'], $_ENV['DATABASE_HOST']
        );
        $user     = $_ENV['DATABASE_USER'];
        $password = $_ENV['DATABASE_PASSWORD'];

        return new PDOConnection($dsn, $user, $password);
    }
}
