FROM php:8.2.3-cli-buster AS base
ARG COMPOSER_VERSION=2.5.4
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=$COMPOSER_VERSION
RUN apt-get update \
    && apt-get install -y git zip unzip librabbitmq-dev libyaml-dev libpq-dev
RUN pecl install amqp-1.11.0 yaml-2.2.2
RUN docker-php-ext-enable amqp yaml
RUN docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install pcntl
RUN docker-php-ext-install pdo_pgsql \
    && docker-php-ext-configure  pgsql -with-pgsql=/usr/local/pgsql
RUN pecl config-set php_ini /usr/local/etc/php/conf.d/over.ini

FROM base AS dev
ARG INFECTION_VERSION=0.27.2
WORKDIR "/app"
RUN apt-get update \
    && apt-get install -y vim wget libicu-dev g++ zlib1g-dev procps
RUN pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl
RUN wget https://github.com/infection/infection/releases/download/$INFECTION_VERSION/infection.phar -O /usr/bin/infection \
	&& chmod +x /usr/bin/infection
ARG DEVELOPER_UID
RUN useradd -m -s /bin/bash -u ${DEVELOPER_UID} developer
USER developer
