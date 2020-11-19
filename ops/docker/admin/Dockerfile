FROM node:14-buster-slim AS development
WORKDIR /app
ENV NODE_ENV=development
ENV HOST='0.0.0.0'
ENV PORT=3000
VOLUME [ "/app" ]
EXPOSE 3000
CMD [ "/bin/sh", "-c", "yarn && yarn develop" ]

FROM node:14-buster-slim AS stage
WORKDIR /app
ENV NODE_ENV=stage
ENV HOST='0.0.0.0'
ENV PORT=3000
COPY src/admin .
COPY --from=admin-stage-artifacts /tmp/node_modules node_modules
COPY --from=admin-stage-artifacts /tmp/build build
EXPOSE 3000
CMD [ "yarn", "start" ]

FROM node:14-buster-slim AS production
WORKDIR /app
ENV NODE_ENV=production
ENV HOST='0.0.0.0'
ENV PORT=3000
COPY src/admin .
COPY --from=admin-production-artifacts /tmp/node_modules node_modules
COPY --from=admin-production-artifacts /tmp/build build
EXPOSE 3000
CMD [ "yarn", "start" ]