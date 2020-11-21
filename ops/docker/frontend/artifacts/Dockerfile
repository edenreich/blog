FROM node:14-buster-slim AS frontend-artifacts
WORKDIR /tmp
COPY src/frontend/yarn.lock .
COPY src/frontend/package.json .
COPY src/frontend/next.config.js .
COPY src/frontend/tsconfig.json .
COPY src/frontend/*.d.ts ./
COPY src/frontend/config/environments/production config/environments/production
COPY src/frontend/interfaces interfaces
COPY src/frontend/components components
COPY src/frontend/pages pages
COPY src/frontend/assets assets
COPY src/frontend/utils utils
RUN apt-get update && \
    apt-get install -y --no-install-recommends build-essential python && \
    yarn global add mkdirp@latest && \
    NODE_ENV=production yarn install --modules-folder ./production_node_modules && \
    yarn install --modules-folder ./development_node_modules
