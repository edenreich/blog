![admin](https://github.com/edenreich/blog/workflows/admin/badge.svg)

## Admin

The Admin UI area to manage content.

Before you can go to Quick Start, you have to run ./up.sh script from the root directory for creating the cluster.

### Quick Start

1. Mount this directory to /app with a node container:
```sh
docker run --rm -it --user 1000:1000 -v ${PWD}:/app -w /app node:16.2.0-alpine3.12 /bin/sh
```
2. Start dev environment:
```sh
yarn dev
```
