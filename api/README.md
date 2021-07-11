## API

### Quick Start

```sh
docker build -t api --target development .
docker run --rm -it -v ${PWD}:/app -p 80:3000 api
```