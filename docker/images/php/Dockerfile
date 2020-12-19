FROM php:7.4-fpm-alpine

RUN rm -rf /var/cache/apk/* && \
    rm -rf /tmp/*

RUN apk update && apk add unzip

# git client, mysql-client
RUN apk add --update --no-cache git mysql-client bash htop

# Configure version constraints
ENV PHP_ENABLE_XDEBUG=0 \
    PATH=/www:/var/www/vendor/bin:/root/.composer/vendor/bin:$PATH \
    TERM=linux \
    VERSION_PRESTISSIMO_PLUGIN=^0.3.7 \
    COMPOSER_ALLOW_SUPERUSER=1

# Add configuration files
COPY image-files/ /

# Add GITHUB_API_TOKEN support for composer
RUN chmod 700 \
        /usr/local/bin/composer

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer.phar \
        --install-dir=/usr/local/bin

# pcntl
RUN docker-php-ext-install pcntl

RUN sed -i -e 's/listen.*/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.conf

RUN echo "expose_php=0" > /usr/local/etc/php/php.ini
RUN echo "memory_limit=-1" > /usr/local/etc/php/php.ini

ENV PHP_EXTRA_CONFIGURE_ARGS --enable-fpm --with-fpm-user=1000 --with-fpm-group=1000
USER 1000

WORKDIR /var/www

CMD ["php-fpm"]
