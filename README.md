To make the bundle work: 
1. Add it to bundles.php
2. Create a service definition of a repository class implementing `CompanyRepositoryInterface` with alias `cogitoweb.repository.company`. For example:
```
cogitoweb.repository.company:
    alias: App\Repository\CompanyRepository
```
3\. Define parameters for a global connection. For example:
```
parameters:
    user: 'abc'
    password: 'zzz'
    host: 'kkk'
    dbname: 'ttt'
```