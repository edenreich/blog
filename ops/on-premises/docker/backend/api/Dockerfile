FROM composer:1.9 AS dev-builder
WORKDIR /app
COPY src/backend/api/composer.* ./
RUN composer install

FROM composer:1.9 AS builder
WORKDIR /app
COPY src/backend/api/composer.* ./
RUN composer install --no-dev

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
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.client_port=9000' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.log_level=0' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
VOLUME [ "/app" ]

FROM php-common AS src
COPY --chown=www-data:www-data src/backend/api .

FROM src AS src-with-devtools
COPY --chown=www-data:www-data --from=dev-builder /app/vendor vendor
COPY --chown=www-data:www-data --from=dev-builder /usr/bin/composer /usr/bin/composer
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

FROM src AS production
ENV APP_ENV=prod
COPY --chown=www-data:www-data --from=builder /app/vendor vendor
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
