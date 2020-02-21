FROM php:7.3-cli-alpine

# configure and enable our php extensions
RUN apk --update upgrade \
    && apk add --no-cache autoconf automake make gcc g++ icu-dev \
    #&& pecl install xdebug-2.9.1 \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        opcache \
        intl

# install composer and config our php.ini
RUN php -r "copy('http://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/bin --filename=composer && \
    php -r "unlink('composer-setup.php');" && \
    cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

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