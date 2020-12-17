FROM composer AS dev-builder
WORKDIR /app
COPY src/backend/api/composer.* ./
RUN composer install

FROM composer AS builder
WORKDIR /app
COPY src/backend/api/composer.* ./
RUN composer install --no-dev

FROM php:8.0.0-fpm-alpine3.12 AS common
RUN apk add --update bash nginx supervisor postgresql-dev
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    docker-php-ext-install pdo_pgsql && \
    apk del .build-deps
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
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.client_port=9000' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.log=/tmp/xdebug.log' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
VOLUME [ "/var/www" ]

FROM common AS test
WORKDIR /var/www/api
ENV APP_ENV=test
COPY src/backend/api .
COPY --from=dev-builder /app/vendor .
COPY --from=dev-builder /usr/bin/composer /usr/bin/composer
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
CMD [ "php", "bin/phpunit" ]

FROM common AS stage
WORKDIR /var/www/api
ENV APP_ENV=prod
COPY src/backend/api .
COPY --from=builder /app/vendor .
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

FROM common AS production
WORKDIR /var/www/api
ENV APP_ENV=prod
COPY src/backend/api .
COPY --from=builder /app/vendor .
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
