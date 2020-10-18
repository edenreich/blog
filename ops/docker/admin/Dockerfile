ARG APP_ENV=production

FROM node:13.10.1-buster-slim AS builder
WORKDIR /tmp
ARG APP_ENV
ENV NODE_ENV=$APP_ENV

COPY src/admin/config/environments/$APP_ENV ./config/environments/$APP_ENV
COPY src/admin/yarn.lock .
COPY src/admin/package.json .

RUN apt-get update && \
    apt-get install -y --no-install-recommends build-essential python && \
    yarn --network-timeout 1000000 && \
    yarn build && \
    apt-get remove --auto-remove -y build-essential python && \
    rm -rf /var/lib/apt/lists/*

FROM node:13.10.1-buster-slim AS development
WORKDIR /app
ARG APP_ENV
ENV HOST='0.0.0.0'
ENV PORT=3000
ENV NODE_ENV=$APP_ENV
VOLUME [ "/app" ]
EXPOSE 3000
CMD [ "yarn", "start" ]

FROM node:13.10.1-buster-slim AS stage
WORKDIR /app
ARG APP_ENV
ENV HOST='0.0.0.0'
ENV PORT=3000
ENV NODE_ENV=$APP_ENV
COPY src/admin .
COPY --from=builder /tmp/node_modules node_modules
COPY --from=builder /tmp/build build
EXPOSE 3000
CMD [ "yarn", "start" ]

FROM node:13.10.1-buster-slim AS production
WORKDIR /app
ARG APP_ENV
ENV HOST='0.0.0.0'
ENV PORT=3000
ENV NODE_ENV=$APP_ENV
COPY src/admin .
COPY --from=builder /tmp/node_modules node_modules
COPY --from=builder /tmp/build build
EXPOSE 3000
CMD [ "yarn", "start" ]