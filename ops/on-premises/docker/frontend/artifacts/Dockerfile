FROM node:15.2.1-buster-slim AS frontend-artifacts
WORKDIR /tmp
COPY frontend/yarn.lock .
COPY frontend/package.json .
COPY frontend/next.config.js .
COPY frontend/tsconfig.json .
COPY frontend/*.d.ts ./
COPY frontend/config/environments/production config/environments/production
COPY frontend/interfaces interfaces
COPY frontend/components components
COPY frontend/pages pages
COPY frontend/styles styles
COPY frontend/utils utils
RUN apt-get update && \
    apt-get install -y build-essential python && \
    yarn upgrade && yarn install && \
    yarn install --modules-folder ./development_node_modules && \
    NODE_ENV=production yarn install --modules-folder ./production_node_modules
