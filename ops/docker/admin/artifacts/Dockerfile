FROM node:12-buster-slim AS admin-stage-artifacts
WORKDIR /tmp
ENV NODE_ENV=production
COPY src/admin/config/environments/stage ./config/environments/stage
COPY src/admin/yarn.lock .
COPY src/admin/package.json .
RUN apt-get update && \
    apt-get install -y build-essential libpng-dev python && \
    yarn --network-timeout 1000000 && \
    yarn build && \
    apt-get remove --auto-remove -y build-essential libpng-dev python && \
    rm -rf /var/lib/apt/lists/*

FROM node:12-buster-slim AS admin-production-artifacts
WORKDIR /tmp
ENV NODE_ENV=production
COPY src/admin/config/environments/production ./config/environments/production
COPY src/admin/yarn.lock .
COPY src/admin/package.json .
RUN apt-get update && \
    apt-get install -y build-essential libpng-dev python && \
    yarn --network-timeout 1000000 && \
    yarn build && \
    apt-get remove --auto-remove -y build-essential libpng-dev python && \
    rm -rf /var/lib/apt/lists/*
