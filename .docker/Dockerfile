ARG BASE_IMAGE
FROM ${BASE_IMAGE}

ARG COMPOSER_VERSION=2.4.4

RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer --version=$COMPOSER_VERSION

RUN apt-get update && \
    apt-get install -y git vim unzip
#    libzip-dev libcurl4-openssl-dev pkg-config libssl-dev libxml2-dev unzip librabbitmq-dev


RUN echo "su - developer" > /root/.bashrc
ARG DEVELOPER_UID
RUN useradd -m -s /bin/bash -u ${DEVELOPER_UID} developer
USER developer

WORKDIR "/app"
