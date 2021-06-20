![api](https://github.com/edenreich/blog/workflows/api/badge.svg)

## API

Temporary api, will be deprecated / replaced into smaller services. most likely to be written in typescript.

### Quick Start

1. Copy a service account into ./keys/service_account.json
2. Restart the cluster following the main guide [blog](../#README.md)
3. Run migrations in one of the pods:
```sh
kubectl exec -it api-latest-<hash> -- sh
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```
