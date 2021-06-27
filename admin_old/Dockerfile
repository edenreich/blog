<<<<<<< HEAD
FROM node:16.2.0-alpine3.12 AS base
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
=======
FROM node:alpine3.12 AS yarn-builder
WORKDIR /app
ENV NODE_ENV=prod
COPY src/backend/admin/package.* ./
COPY src/backend/admin/webpack.config.js .
COPY src/backend/admin/assets assets
RUN yarn install && yarn build

FROM php-common AS development
VOLUME [ "/app" ]
ENV APP_ENV=dev
USER root
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
# RUN apk add --update --no-cache --virtual .build-deps $PHPIZE_DEPS && \
#     pecl install xdebug && \
#     docker-php-ext-enable xdebug && \
#     apk del .build-deps
# RUN apk add --no-cache nodejs npm && npm install --global yarn
# RUN echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
#     echo 'xdebug.client_port=9000' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
#     echo 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
#     echo 'xdebug.log_level=0' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
#     echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
USER www-data:1000
CMD [ "bin/console", "swoole:server:run" ]

FROM php-common AS src
COPY --chown=www-data src/backend/admin .

FROM src AS src-with-devtools
WORKDIR /app
ENV NODE_ENV=dev
ENV APP_ENV=dev
USER root
RUN apk add --no-cache nodejs npm && npm install --global yarn
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
USER www-data
RUN yarn install && yarn build && rm -rf /home/www-data/.cache
RUN composer install

FROM src AS production
ENV APP_ENV=prod
USER root
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
USER www-data
RUN composer install --no-dev
COPY --chown=www-data --from=yarn-builder /app/public/build public/build
CMD [ "bin/console", "swoole:server:run" ]
>>>>>>> develop
