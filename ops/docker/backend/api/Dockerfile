FROM composer AS dev-builder
WORKDIR /app
COPY src/backend/api/composer.* ./
RUN composer install

FROM composer AS builder
WORKDIR /app
COPY src/backend/api/composer.* ./
RUN composer install --no-dev

FROM php:8.0.0-fpm-alpine3.12 AS common
RUN apk add --update bash nginx supervisor
RUN mkdir /run/nginx
COPY ops/docker/backend/api/supervisor.d /etc/supervisor.d
COPY ops/docker/backend/api/nginx/default.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD [ "supervisord", "--pidfile", "/run/supervisord.pid", "-c", "/etc/supervisord.conf" ]

FROM common AS development
WORKDIR /var/www/api
ENV APP_ENV=dev
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apk del .build-deps
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
COPY --from=dev-builder /usr/bin/composer /usr/bin/composer
VOLUME [ "/var/www" ]

FROM common AS test
WORKDIR /var/www/api
ENV APP_ENV=test
COPY src/api .
COPY --from=dev-builder /app .

FROM common AS stage
WORKDIR /var/www/api
ENV APP_ENV=prod
COPY src/api .
COPY --from=builder /app .

FROM common AS production
WORKDIR /var/www/api
ENV APP_ENV=prod
COPY src/api .
COPY --from=builder /app .
