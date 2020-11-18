ARG APP_ENV=production

FROM node:12-buster-slim AS builder
WORKDIR /tmp
ARG APP_ENV
ENV NODE_ENV=$APP_ENV
COPY src/admin/config/environments/$APP_ENV ./config/environments/$APP_ENV
COPY src/admin/yarn.lock .
COPY src/admin/package.json .
RUN apt-get update && \
    apt-get install -y build-essential libpng-dev python && \
    yarn --network-timeout 1000000 && \
    yarn build && \
    apt-get remove --auto-remove -y build-essential libpng-dev python && \
    rm -rf /var/lib/apt/lists/*
