FROM node:16.2.0-alpine3.12 AS base
USER node
ENV NODE_ENV=dev
WORKDIR /app

FROM base AS src
COPY --chown=node:node ./src ./src
COPY --chown=node:node *.json .
COPY --chown=node:node webpack.config.js .

FROM src AS builder
ENV NODE_ENV=prod
RUN yarn install && yarn run build

FROM base AS development
VOLUME [ "/app" ]
CMD [ "yarn", "dev" ]

FROM src AS src-with-devtools
ENV NODE_ENV=dev
RUN yarn install
CMD [ "yarn", "start" ]

FROM base AS staging
ENV NODE_ENV=prod
COPY --chown=node:node --from=builder /app/build .
CMD [ "yarn", "start" ]

FROM base AS production
ENV NODE_ENV=prod
COPY --chown=node:node --from=builder /app/build .
CMD [ "yarn", "start" ]
