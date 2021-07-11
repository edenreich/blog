## API

### Quick Start

1. Build a container image with development target:
```sh
docker build -t api --target development .
```

2. Run it with mounted volume:
```sh
docker run --rm -it -v ${PWD}:/app -p 80:3000 api
```

3. Create a local database:
```sh
docker run --rm -it -e POSTGRES_DB=api -e POSTGRES_PASSWORD=secret postgres
```

### Documentation

Start the server and go to /docs

### Testing Endpoints

On VS-Code install [Http REST Client](https://marketplace.visualstudio.com/items?itemName=humao.rest-client) and open [http/client/README.md](http/client/#README.md)
