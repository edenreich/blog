FROM composer AS dev-builder
WORKDIR /app
COPY src/api/composer.* .
RUN composer install

FROM composer AS builder
WORKDIR /app
COPY src/api/composer.* .
RUN composer install --no-dev

FROM php:8.0.0-fpm-alpine3.12 AS common
RUN apk add --update nginx supervisor
RUN mkdir /run/nginx && \
    mkdir /var/log/php-fpm
COPY ops/docker/backend/api/supervisor.d /etc/supervisor.d
COPY ops/docker/backend/api/nginx/default.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD [ "supervisord", "--pidfile", "/run/supervisord.pid", "-c", "/etc/supervisord.conf" ]

FROM common AS development
ENV APP_ENV=dev
WORKDIR /var/www
VOLUME [ "/var/www" ]

FROM common AS test
ENV APP_ENV=test
WORKDIR /var/www
COPY src/api .
COPY --from=dev-builder /app api

FROM common AS stage
ENV APP_ENV=prod
WORKDIR /var/www
COPY src/api .
COPY --from=builder /app api

FROM common AS production
ENV APP_ENV=prod
WORKDIR /var/www
COPY src/api .
COPY --from=builder /app api
