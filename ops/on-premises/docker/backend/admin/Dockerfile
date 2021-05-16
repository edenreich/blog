FROM composer:1.9 AS composer-builder
WORKDIR /app
COPY src/backend/admin/composer.* ./
RUN composer install --no-dev

FROM node:alpine3.12 AS yarn-builder
WORKDIR /app
ENV NODE_ENV=prod
COPY src/backend/admin/package.* ./
COPY src/backend/admin/webpack.config.js .
COPY src/backend/admin/assets assets
RUN yarn install && yarn build

FROM php-common AS development
USER root
WORKDIR /app
ENV APP_ENV=dev
RUN apk add --update --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apk del .build-deps
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
COPY --from=composer:1.9 /usr/bin/composer /usr/bin/composer
RUN apk add --no-cache nodejs npm && npm install --global yarn
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.client_port=9000' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.log_level=0' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
VOLUME [ "/app" ]

FROM php-common AS src
COPY --chown=www-data:www-data src/backend/admin .

FROM src AS src-with-devtools
ENV NODE_ENV=dev
ENV APP_ENV=dev
USER root
RUN apk add --no-cache nodejs npm && npm install --global yarn
COPY --from=composer:1.9 /usr/bin/composer /usr/bin/composer
USER www-data
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN yarn install && yarn build
RUN composer install

FROM src AS production
ENV APP_ENV=prod
COPY --chown=www-data:www-data --from=composer-builder /app/vendor vendor
COPY --chown=www-data:www-data --from=yarn-builder /app/public/build public/build
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
