FROM node:13.10.1-buster-slim

WORKDIR /app

ENV PORT=3000

RUN apt-get update && apt-get install -y --no-install-recommends build-essential python 

COPY src/admin .

RUN yarn --production \
    && yarn build

EXPOSE 3000

CMD [ "yarn", "start" ]