FROM node:14-buster-slim AS development
WORKDIR /app
ENV APP_ENV=development
ENV NODE_ENV=development
EXPOSE 3000
CMD [ "/bin/sh", "-c", "yarn && yarn dev" ]

FROM node:14-buster-slim AS stage
WORKDIR /app
ENV APP_ENV=stage
ENV NODE_ENV=production
COPY src/frontend .
COPY --from=frontend-artifacts /tmp/.next .next
COPY --from=frontend-artifacts /tmp/node_modules node_modules
EXPOSE 3000
CMD [ "yarn", "start" ]

FROM node:14-buster-slim AS production
WORKDIR /app
ENV APP_ENV=production
ENV NODE_ENV=production
COPY src/frontend .
COPY --from=frontend-artifacts /tmp/.next .next
COPY --from=frontend-artifacts /tmp/node_modules node_modules
EXPOSE 3000
CMD [ "yarn", "start" ]
