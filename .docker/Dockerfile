FROM php:8.2.3-cli-buster AS base
ARG COMPOSER_VERSION=2.5.4
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=$COMPOSER_VERSION
RUN apt-get update && apt-get install -y git zip unzip librabbitmq-dev libyaml-dev
RUN pecl install amqp-1.11.0 yaml-2.2.2
RUN docker-php-ext-enable amqp yaml
RUN pecl config-set php_ini /usr/local/etc/php/conf.d/over.ini


FROM base AS dev
WORKDIR "/app"
RUN apt-get update && apt-get install -y vim wget libicu-dev g++ zlib1g-dev
RUN pecl install xdebug-3.2.0
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN wget https://github.com/infection/infection/releases/download/0.26.19/infection.phar -O /usr/bin/infection
RUN chmod +x /usr/bin/infection
ARG DEVELOPER_UID
RUN useradd -m -s /bin/bash -u ${DEVELOPER_UID} developer
USER developer
