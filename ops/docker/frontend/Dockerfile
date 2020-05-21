FROM node:13.10.1-buster-slim AS builder

WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends build-essential python 

COPY src/frontend .

RUN yarn && \ 
    yarn build && \
    yarn --production

FROM node:13.10.1-buster-slim

WORKDIR /app

COPY --from=builder /app .

EXPOSE 3000

CMD [ "yarn", "start" ]
