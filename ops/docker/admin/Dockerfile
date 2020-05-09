FROM node:13.10.1-buster-slim

WORKDIR /app

ENV PORT=3000

RUN yarn --production

COPY src/admin /app

EXPOSE 3000

CMD [ "yarn", "start" ]