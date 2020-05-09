FROM node:13.10.1-buster-slim

WORKDIR /app

ENV PORT=3000

COPY src/admin .

RUN yarn --production \
    && yarn build

EXPOSE 3000

CMD [ "yarn", "start" ]