FROM node:14-buster-slim AS builder
RUN apt-get update && \
    apt-get install -y build-essential python

FROM builder AS admin-artifacts
WORKDIR /tmp
ENV NODE_ENV=production
COPY src/admin/yarn.lock .
COPY src/admin/package.json .
RUN yarn upgrade && yarn --network-timeout 1000000
