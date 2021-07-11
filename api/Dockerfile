FROM node:16.2.0-alpine3.12 AS base
USER node
ENV NODE_ENV=development
WORKDIR /app

FROM base AS src
COPY --chown=node:node src src
COPY --chown=node:node *.json .

FROM src AS builder
RUN yarn && NODE_ENV=production yarn build && \
    NODE_ENV=production yarn

FROM base AS development
ENV APP_ENV=development
ENV NODE_ENV=development
ENV DATABASE_URL='postgres://postgres:secret@postgres/blog_api'
VOLUME [ "/app" ]
CMD [ "yarn", "dev" ]

FROM base AS staging
ENV APP_ENV=staging
ENV NODE_ENV=production
ENV DATABASE_URL='postgres://postgres:secret@postgres/blog_api_staging'
COPY --chown=node:node --from=builder /app/node_modules /app/node_modules
COPY --chown=node:node --from=builder /app/dist /app/dist
COPY --chown=node:node --from=builder /app/package.json /app/package.json
CMD [ "yarn", "start" ]

FROM base AS production
ENV APP_ENV=production
ENV NODE_ENV=production
ENV DATABASE_URL='postgres://postgres:secret@postgres/blog_api'
COPY --chown=node:node --from=builder /app/node_modules /app/node_modules
COPY --chown=node:node --from=builder /app/dist /app/dist
COPY --chown=node:node --from=builder /app/package.json /app/package.json
CMD [ "yarn", "start" ]