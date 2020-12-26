FROM composer AS dev-builder
WORKDIR /app
COPY src/backend/api/composer.* ./
RUN composer install

FROM composer AS builder
WORKDIR /app
COPY src/backend/api/composer.* ./
RUN composer install --no-dev

FROM php:7.4-fpm-alpine AS common
RUN apk add --update bash nginx supervisor postgresql-dev
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    docker-php-ext-install pdo_pgsql && \
    apk del .build-deps
RUN mkdir /run/nginx
COPY ops/docker/backend/api/supervisor.d/programs /etc/supervisor.d
COPY ops/docker/backend/api/supervisor.d/supervisord.conf /etc/supervisord.conf
COPY ops/docker/backend/api/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ops/docker/backend/api/nginx/nginx.conf /etc/nginx/nginx.conf
RUN chown www-data:www-data /etc/supervisord.conf /etc/supervisor.d
RUN chown -R www-data:www-data /run /var/lib/nginx /usr/local/etc/php /var/log
RUN echo 'access.format = "[%t] %m %{REQUEST_SCHEME}e://%{HTTP_HOST}e%{REQUEST_URI}e %f pid:%p took:%ds mem:%{mega}Mmb cpu:%C%% status:%s {%{REMOTE_ADDR}e|%{HTTP_USER_AGENT}e}"' >> /usr/local/etc/php-fpm.d/www.conf
EXPOSE 8080
USER www-data
WORKDIR /var/www/api
CMD [ "supervisord", "-c", "/etc/supervisord.conf" ]

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
    echo 'xdebug.log_level=0' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
VOLUME [ "/var/www/api" ]

FROM common AS src
COPY --chown=www-data:www-data src/backend/api .

# FROM src AS lint
# ENV APP_ENV=dev
# COPY --chown=www-data:www-data --from=dev-builder /app/vendor vendor
# RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
# CMD [ "/bin/sh", "-c", "bin/console lint:yaml config && ./vendor/bin/php-cs-fixer fix --diff --diff-format=udiff --dry-run --verbose -- src tests migrations" ]

FROM src AS test
ENV APP_ENV=test
COPY --chown=www-data:www-data --from=dev-builder /app/vendor vendor
COPY --chown=www-data:www-data --from=dev-builder /usr/bin/composer /usr/bin/composer
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
CMD [ "/bin/sh", "-c", "supervisord -c /etc/supervisord.conf & bin/phpunit" ]

FROM src AS production
ENV APP_ENV=prod
COPY --chown=www-data:www-data --from=builder /app/vendor vendor
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
