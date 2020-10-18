ARG APP_ENV=production

FROM node:13.10.1-buster-slim AS builder
WORKDIR /tmp
ARG APP_ENV
COPY src/frontend/yarn.lock .
COPY src/frontend/package.json .
COPY src/frontend/next.config.js .
COPY src/frontend/tsconfig.json .
COPY src/frontend/*.d.ts ./
COPY src/frontend/config/environments/$APP_ENV config/environments/$APP_ENV
COPY src/frontend/interfaces interfaces
COPY src/frontend/components components
COPY src/frontend/pages pages
COPY src/frontend/assets assets
RUN apt-get update && \
    apt-get install -y --no-install-recommends build-essential python && \
    yarn && \
    yarn build && \
    NODE_ENV=production yarn --production && \
    apt-get remove --auto-remove -y build-essential python && \
    rm -rf /var/lib/apt/lists/*

FROM node:13.10.1-buster-slim AS development
WORKDIR /app
ARG APP_ENV
ENV APP_ENV=$APP_ENV
ENV NODE_ENV=development
VOLUME [ "/app" ]
EXPOSE 3000
CMD [ "/bin/sh", "-c", "yarn && yarn dev" ]

FROM node:13.10.1-buster-slim AS stage
WORKDIR /app
ARG APP_ENV
ENV APP_ENV=$APP_ENV
ENV NODE_ENV=production
COPY src/frontend .
COPY --from=builder /tmp/.next .next
COPY --from=builder /tmp/node_modules node_modules
EXPOSE 3000
CMD [ "yarn", "start" ]

FROM node:13.10.1-buster-slim AS production
WORKDIR /app
ARG APP_ENV
ENV APP_ENV=$APP_ENV
ENV NODE_ENV=production
COPY src/frontend .
COPY --from=builder /tmp/.next .next
COPY --from=builder /tmp/node_modules node_modules
EXPOSE 3000
CMD [ "yarn", "start" ]
