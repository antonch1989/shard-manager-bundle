To make the bundle work: 
1. Add it to bundles.php
2. Create a service definition of a repository class implementing `CompanyRepositoryInterface` with alias `cogitoweb.repository.company`. For example:
```
cogitoweb.repository.company:
    alias: App\Repository\CompanyRepository
```
3\. Define a full set of database connection env variables instead of DATABASE_URL. For example:

```
DATABASE_HOST=shard_app_mysql
DATABASE_PORT=3306
DATABASE_USER=app1
DATABASE_PASSWORD=app1
DATABASE_DRIVER=pdo_mysql
```