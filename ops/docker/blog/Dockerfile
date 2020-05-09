FROM node:13.10.1-buster-slim AS builder

WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends build-essential python 

COPY src .

RUN yarn && \ 
    yarn build && \
    yarn --production

FROM node:13.10.1-buster-slim

WORKDIR /app

ARG MAILGUN_API_KEY
ARG MAILGUN_DOMAIN=mg.eden-reich.com

ENV MAILGUN_API_KEY=$MAILGUN_API_KEY
ENV MAILGUN_DOMAIN=$MAILGUN_DOMAIN

COPY --from=builder /app .

EXPOSE 3000

CMD [ "yarn", "start" ]
