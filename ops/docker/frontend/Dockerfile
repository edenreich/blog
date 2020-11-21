FROM node:14-buster-slim AS builder
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
COPY --from=frontend-artifacts /tmp/development_node_modules node_modules
RUN apt-get update && \
    apt-get install -y --no-install-recommends build-essential python && \
    NODE_ENV=production yarn build

FROM node:14-buster-slim AS development
WORKDIR /app
ENV PORT=3000
ENV APP_ENV=development
ENV NODE_ENV=development
EXPOSE 3000
CMD [ "yarn", "dev" ]

FROM node:14-buster-slim AS stage
WORKDIR /app
ENV PORT=3000
ENV APP_ENV=stage
ENV NODE_ENV=production
COPY src/frontend .
COPY --from=frontend-artifacts /tmp/node_modules node_modules
COPY --from=builder /tmp/.next .next
EXPOSE 3000
CMD [ "yarn", "start" ]

FROM node:14-buster-slim AS production
WORKDIR /app
ENV PORT=3000
ENV APP_ENV=production
ENV NODE_ENV=production
COPY src/frontend .
COPY --from=frontend-artifacts /tmp/node_modules node_modules
COPY --from=builder /tmp/.next .next
EXPOSE 3000
CMD [ "yarn", "start" ]
