FROM node:14-buster-slim AS builder
RUN apt-get update && \
    apt-get install -y build-essential python

FROM builder AS admin-stage-artifacts
WORKDIR /tmp
ENV NODE_ENV=stage
COPY src/admin/config/env/stage ./config/env/stage
COPY src/admin/yarn.lock .
COPY src/admin/package.json .
RUN yarn --network-timeout 1000000 && \
    yarn build

FROM builder AS admin-production-artifacts
WORKDIR /tmp
ENV NODE_ENV=production
COPY src/admin/config/env/production ./config/env/production
COPY src/admin/yarn.lock .
COPY src/admin/package.json .
RUN yarn --network-timeout 1000000 && \
    yarn build
