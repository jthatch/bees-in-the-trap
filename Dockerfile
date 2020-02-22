ARG PHP_VERSION=7.3

FROM php:${PHP_VERSION}-cli-alpine

# configure and enable our php extensions
RUN apk --update upgrade \
    && apk add --no-cache --virtual .build-deps autoconf automake make gcc g++ icu-dev \
    #&& pecl install xdebug-2.9.1 \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        opcache \
        intl

# install composer and config our php.ini
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# keep it simple for the workdir as we're using cli
WORKDIR /app

# install our stuff
# Optimize for Composer cache
COPY composer.json composer.lock ./
RUN composer install && composer dump-autoload -o

COPY src/ src/
COPY spec/ spec/
COPY bin/console bin/console
COPY beesinthetrap.sh phpspec.yml ./

CMD ["/bin/ash", "/app/beesinthetrap.sh"]