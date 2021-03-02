FROM php:7.4

RUN apt-get update \
    && apt-get install -y librdkafka-dev git zip unzip wget libpq-dev \
    && pecl install rdkafka \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql \
    && docker-php-ext-enable rdkafka \
    # composer
    && curl -s https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer