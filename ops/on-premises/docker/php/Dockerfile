FROM phpswoole/swoole:4.6.7-php7.4-alpine
RUN apk update
RUN apk add --no-cache bash postgresql-dev
RUN apk add --no-cache tzdata && \
    cp /usr/share/zoneinfo/Europe/Berlin /etc/localtime && \
    echo "Europe/Berlin" > /etc/timezone && \
    apk del tzdata
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    docker-php-ext-install pdo_pgsql intl && \
    apk del .build-deps
COPY ops/on-premises/docker/php/php.ini-development /usr/local/etc/php/php.ini-development
COPY ops/on-premises/docker/php/php.ini-production /usr/local/etc/php/php.ini-production
USER www-data
WORKDIR /app
EXPOSE 8080
