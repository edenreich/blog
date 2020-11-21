FROM node:14-buster-slim AS builder
WORKDIR /tmp
RUN apt-get update && \
    apt-get install -y build-essential python

FROM builder AS stage-builder
WORKDIR /tmp
ENV NODE_ENV=stage
COPY src/admin/yarn.lock .
COPY src/admin/package.json .
COPY src/admin/config/env/stage ./config/env/stage
COPY --from=admin-artifacts /tmp/node_modules node_modules
RUN yarn build

FROM builder AS production-builder
WORKDIR /tmp
ENV NODE_ENV=production
COPY src/admin/yarn.lock .
COPY src/admin/package.json .
COPY src/admin/config/env/production ./config/env/production
COPY --from=admin-artifacts /tmp/node_modules node_modules
RUN yarn build

FROM node:14-buster-slim AS development
WORKDIR /app
ENV NODE_ENV=development
ENV HOST='0.0.0.0'
ENV PORT=3000
VOLUME [ "/app" ]
EXPOSE 3000
CMD [ "yarn", "develop" ]

FROM node:14-buster-slim AS stage
WORKDIR /app
ENV NODE_ENV=stage
ENV HOST='0.0.0.0'
ENV PORT=3000
COPY src/admin .
COPY --from=admin-artifacts /tmp/node_modules node_modules
COPY --from=stage-builder /tmp/build build
EXPOSE 3000
CMD [ "yarn", "start" ]

FROM node:14-buster-slim AS production
WORKDIR /app
ENV NODE_ENV=production
ENV HOST='0.0.0.0'
ENV PORT=3000
COPY src/admin .
COPY --from=admin-artifacts /tmp/node_modules node_modules
COPY --from=production-builder /tmp/build build
EXPOSE 3000
CMD [ "yarn", "start" ]