ARG BASE_IMAGE
FROM ${BASE_IMAGE} AS base
ARG COMPOSER_VERSION=2.4.4
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=$COMPOSER_VERSION
RUN apt-get update && apt-get install -y git zip unzip librabbitmq-dev libyaml-dev
RUN pecl install amqp-1.11.0 yaml-2.2.2
RUN docker-php-ext-enable amqp yaml
RUN pecl config-set php_ini /usr/local/etc/php/conf.d/over.ini


FROM base AS dev
RUN apt-get update && apt-get install -y vim
RUN pecl install xdebug-3.1.6
RUN docker-php-ext-enable xdebug
RUN echo "su - developer" > /root/.bashrc
ARG DEVELOPER_UID
RUN useradd -m -s /bin/bash -u ${DEVELOPER_UID} developer
USER developer
WORKDIR "/app"
