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
