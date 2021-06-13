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

FROM node:15.2.1-buster-slim AS builder
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
COPY --from=frontend-artifacts /tmp/development_node_modules node_modules
RUN apt-get update && \
    apt-get install -y --no-install-recommends build-essential python && \
    NODE_ENV=production yarn build

FROM node:15.2.1-buster-slim AS development
WORKDIR /app
VOLUME [ "/app" ]
ENV PORT=3000
ENV APP_ENV=development
ENV NODE_ENV=development
ENV NODE_TLS_REJECT_UNAUTHORIZED=0
RUN apt-get update && apt-get install -y --no-install-recommends build-essential python
EXPOSE 3000
CMD [ "yarn", "dev" ]

FROM node:15.2.1-buster-slim AS production
WORKDIR /app
ENV PORT=3000
ENV APP_ENV=production
ENV NODE_ENV=production
COPY frontend .
COPY --from=frontend-artifacts /tmp/node_modules node_modules
COPY --from=builder /tmp/.next .next
EXPOSE 3000
CMD [ "yarn", "start" ]
