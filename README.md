![admin](https://github.com/edenreich/blog/workflows/admin/badge.svg)
![api](https://github.com/edenreich/blog/workflows/api/badge.svg)
![frontend](https://github.com/edenreich/blog/workflows/frontend/badge.svg)
![authentication](https://github.com/edenreich/blog/workflows/authentication/badge.svg)
![cloud-infrastructure](https://github.com/edenreich/blog/workflows/cloud-infrastructure/badge.svg)
![on-premises-infrastructure](https://github.com/edenreich/blog/workflows/on-premises-infrastructure/badge.svg)

## Blog

My blog for posting interesting content.

- [Blog](#README.md)
  - [Authentication](authentication/#README.md)
  - [API](api/#README.md)
  - [Frontend](frontend/#README.md)


### Prerequisite

* docker
* helm
* kubectl
* k3d

### Quick Start

Generate API RSA keys without passphrase for local development:
```sh
mkdir -p src/backend/api/config/jwt
openssl genrsa -out src/backend/api/config/jwt/private.pem 4096
openssl rsa -in src/backend/api/config/jwt/private.pem -out src/backend/api/config/jwt/public.pem -pubout
```

To startup a cluster run:
```sh
./up.sh
```

To cleanup the local cluster, run:
```sh
./down.sh
```

Enter one of the pods of the admin and api deployment and run:
```sh
bin/console doctrine:migration:migrate
bin/console doctrine:fixtures:load
```
