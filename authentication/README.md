![authentication](https://github.com/edenreich/blog/workflows/authentication/badge.svg)

## Authentication Service

A JWT authentication service written in typescript.

### Quick Start

Just run:
```
yarn dev
```

### Endpoints

| Method  | Path | Description |
| ------------- | ------------- | ------------- |
| GET,HEAD | /api/authentication/healthcheck | Verify the health of the service |
| POST | /api/authentication/jwt | Fetch a signed JWT |
| POST | /api/authentication/authorize | Verify the signed JWT |

### Development

On development environment it's possible to authenticate with the following credentials:
```sh
username: admin@gmail.com
password: admin
```

To generate an access token for this credentials run:
```sh
curl -s -X POST -H 'Content-Type: application/json' http://dev-api.eden-reich.com/api/authentication/jwt -d '{"username":"admin@gmail.com","password":"admin"}' | jq .token | tr -d '"'
```

To authenticate with that generated refreshed JWT:
```sh
curl -X POST -H 'Content-Type: application/json' -H 'Authorization: Bearer <token>' http://dev-api.eden-reich.com/api/authentication/authorize
```

You can combine both for quick testing:
```sh
TOKEN=`curl -s -X POST -H 'Content-Type: application/json' http://dev-api.eden-reich.com/api/authentication/jwt -d '{"username":"admin@gmail.com","password":"admin"}' | jq .token | tr -d '"'` \
  curl -X POST -H 'Content-Type: application/json' -H "Authorization: Bearer $TOKEN" http://dev-api.eden-reich.com/api/authentication/authorize
```
