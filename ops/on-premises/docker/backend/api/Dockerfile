FROM php-common AS development
VOLUME [ "/app" ]
WORKDIR /app
ENV APP_ENV=dev
USER root
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
# RUN apk add --update --no-cache --virtual .build-deps $PHPIZE_DEPS && \
#     pecl install xdebug && \
#     docker-php-ext-enable xdebug && \
#     apk del .build-deps
# RUN echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
#     echo 'xdebug.client_port=9000' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
#     echo 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
#     echo 'xdebug.log_level=0' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
#     echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
USER www-data:1000
CMD [ "bin/console", "swoole:server:run" ]

FROM php-common AS src
COPY --chown=www-data src/backend/api .

FROM src AS src-with-devtools
USER root
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
USER www-data
RUN composer install


FROM src AS production
ENV APP_ENV=prod
USER root
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
USER www-data
RUN composer install --no-dev
CMD [ "bin/console", "swoole:server:run" ]
