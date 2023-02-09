FROM php:8.1-fpm

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it

RUN apt-get update && \
    apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl git unzip netcat libxml2-dev libpq-dev libzip-dev librabbitmq-dev && \
    pecl install apcu && \
    pecl install amqp \

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install zip opcache intl pdo pdo_pgsql pgsql pdo_mysql
RUN docker-php-ext-enable apcu pdo_pgsql sodium pdo_mysql amqp && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl -sS https://getcomposer.org/installer | php -- \
--install-dir=/usr/bin --filename=composer \

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY docker-entrypoint.sh /web/docker-entrypoint.sh
RUN chmod +x /web/docker-entrypoint.sh

CMD php-fpm

EXPOSE 9000
ENTRYPOINT ["/web/docker-entrypoint.sh"]
