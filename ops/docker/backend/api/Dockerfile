FROM composer AS dev-builder
WORKDIR /app
COPY src/api/composer.* .
RUN composer install

FROM composer AS builder
WORKDIR /app
COPY src/api/composer.* .
RUN composer install --no-dev

FROM php:8.0.0-fpm-alpine3.12 AS common

FROM common AS development
ENV APP_ENV=development
WORKDIR /app
VOLUME [ "/app" ]

FROM common AS test
ENV APP_ENV=test
WORKDIR /app
COPY src/api .
COPY --from=dev-builder /app .

FROM common AS stage
ENV APP_ENV=prod
WORKDIR /app
COPY src/api .
COPY --from=builder /app .

FROM common AS production
ENV APP_ENV=prod
WORKDIR /app
COPY src/api .
COPY --from=builder /app .
