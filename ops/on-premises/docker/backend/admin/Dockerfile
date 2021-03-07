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

FROM php:7.4.14-fpm-alpine3.12 AS common
USER root
RUN apk update
RUN apk add --no-cache bash nginx supervisor postgresql-dev
RUN apk add --no-cache tzdata && \
    cp /usr/share/zoneinfo/Europe/Berlin /etc/localtime && \
    echo "Europe/Berlin" > /etc/timezone && \
    apk del tzdata
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    docker-php-ext-install pdo_pgsql intl && \
    apk del .build-deps
RUN mkdir /run/nginx
COPY ops/on-premises/docker/backend/admin/php/php.ini-development /usr/local/etc/php/php.ini-development
COPY ops/on-premises/docker/backend/admin/php/php.ini-production /usr/local/etc/php/php.ini-production
COPY ops/on-premises/docker/backend/admin/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ops/on-premises/docker/backend/admin/supervisor.d/programs /etc/supervisor.d
COPY ops/on-premises/docker/backend/admin/supervisor.d/supervisord.conf /etc/supervisord.conf
COPY ops/on-premises/docker/backend/admin/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ops/on-premises/docker/backend/admin/nginx/nginx.conf /etc/nginx/nginx.conf
RUN chown www-data:www-data /etc/supervisord.conf /etc/supervisor.d
RUN chown -R www-data:www-data /run /var/lib/nginx /usr/local/etc/php /var/log
USER www-data
WORKDIR /var/www/admin
EXPOSE 8080
CMD [ "supervisord", "-c", "/etc/supervisord.conf" ]

FROM common AS development
USER root
WORKDIR /var/www/admin
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
VOLUME [ "/var/www/admin" ]

FROM common AS src
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
