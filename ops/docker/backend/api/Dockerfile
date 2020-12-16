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
RUN mkdir /run/nginx
COPY ops/docker/backend/api/supervisor.d /etc/supervisor.d
COPY ops/docker/backend/api/nginx/default.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD [ "supervisord", "--pidfile", "/run/supervisord.pid", "-c", "/etc/supervisord.conf" ]

FROM common AS development
WORKDIR /var/www
ENV APP_ENV=dev
VOLUME [ "/var/www" ]

FROM common AS test
WORKDIR /var/www
ENV APP_ENV=test
COPY src/api .
COPY --from=dev-builder /app api

FROM common AS stage
WORKDIR /var/www
ENV APP_ENV=prod
COPY src/api .
COPY --from=builder /app api

FROM common AS production
WORKDIR /var/www
ENV APP_ENV=prod
COPY src/api .
COPY --from=builder /app api
