FROM php:8.0-fpm

RUN apt-get update && \
    apt-get install zip unzip

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app

CMD composer i -o;php-fpm;

