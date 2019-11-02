To make the bundle work: 
1. Add it to bundles.php
2. Create a service definition of a repository class implementing `CompanyRepositoryInterface` with alias `cogitoweb.repository.company`. For example:
```
cogitoweb.repository.company:
    alias: App\Repository\CompanyRepository
```
