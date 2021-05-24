FROM node:16.2.0-buster-slim AS base
USER node
ENV NODE_ENV=dev
WORKDIR /app

FROM base AS src
COPY --chown=node:node ./src ./src
COPY --chown=node:node *.json .

FROM src AS builder
RUN npm install && npm run build

FROM base AS development
USER 1000:1000
VOLUME [ "/app" ]
CMD [ "npm", "run", "dev" ]

FROM base AS staging
ENV NODE_ENV=prod
COPY --chown=node:node --from=builder /app/build .
CMD [ "npm", "start" ]

FROM base AS production
ENV NODE_ENV=prod
COPY --chown=node:node --from=builder /app/build .
CMD [ "npm", "start" ]
