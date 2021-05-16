FROM php:7.4.14-fpm-alpine3.12
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
COPY ops/on-premises/docker/php/php.ini-development /usr/local/etc/php/php.ini-development
COPY ops/on-premises/docker/php/php.ini-production /usr/local/etc/php/php.ini-production
COPY ops/on-premises/docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ops/on-premises/docker/php/supervisor.d/programs /etc/supervisor.d
COPY ops/on-premises/docker/php/supervisor.d/supervisord.conf /etc/supervisord.conf
COPY ops/on-premises/docker/php/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ops/on-premises/docker/php/nginx/nginx.conf /etc/nginx/nginx.conf
RUN chown www-data:www-data /etc/supervisord.conf /etc/supervisor.d
RUN chown -R www-data:www-data /run /var/lib/nginx /usr/local/etc/php /var/log
USER www-data
WORKDIR /app
EXPOSE 8080
CMD [ "supervisord", "-c", "/etc/supervisord.conf" ]
