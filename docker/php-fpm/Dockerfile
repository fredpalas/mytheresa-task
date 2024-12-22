FROM public.ecr.aws/docker/library/php:8.3-fpm-bookworm@sha256:a3b7b7770e610385103740c765a3ad7fdf7f0168e12eb6b1c9279aadfca9f96b AS base

SHELL ["/bin/bash", "-euxo", "pipefail", "-c"]

ARG TIMEZONE=UTC
ARG XDEBUG_CLIENT_HOST=host.docker.internal
ARG XDEBUG_START_WITH_REQUEST=trigger
ARG XDEBUG_CLIENT_PORT=9003
ARG WWWUSER

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt-get update && apt-get install -y --no-install-recommends \
        openssl \
        git \
        unzip \
        sqlite3 \
        # For ext-zip
        zlib1g-dev \
        libzip-dev \
        mariadb-client \
        wget \
        libcurl4-openssl-dev\
        pkg-config\
        libssl-dev\
        libicu-dev \
        libonig-dev \
        # For fixing internal docker host for linux
        net-tools \
        # For configuring locales
        locales \
        # For mysqldump
        mariadb-client \
        libssl-dev \
        pkg-config \
        # Clean up apt
        && apt-get clean \
        && rm -rf /var/lib/apt/lists/* \
        # Install Composer
        && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
        && mkdir /run/php \
        # Set timezone
        && ln -snf "/usr/share/zoneinfo/${TIMEZONE}" /etc/localtime && echo "${TIMEZONE}" > /etc/timezone \
        && "date" \
        && chmod +x /usr/local/bin/install-php-extensions \
        # Type docker-php-ext-install to see available extensions
        && docker-php-ext-install pdo pdo_mysql \
        # Enable APCu
        && pecl install apcu \
        && docker-php-ext-enable apcu \
        # Install OPCache
        && docker-php-ext-install opcache \
        # Install mbstring
        && install-php-extensions mbstring \
        # Install zip
        && docker-php-ext-install zip \
        && docker-php-ext-install bcmath \
        && docker-php-ext-install sockets \
        # PCNTL for Laravel horizon
        && docker-php-ext-install pcntl \
        && docker-php-ext-configure pcntl --enable-pcntl \
        # Create user
        && ORIG_UID=$(id -u www-data) && usermod -u "${WWWUSER:-$ORIG_UID}" www-data \
        # Configure locales
        && sed -i 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen \
        && locale-gen en_US.UTF-8 \
        && chown www-data:www-data /var/www
# Install Extension for get Cached
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl
# Copy PHP configuration
COPY ./docker/php-fpm/conf.d/ "$PHP_INI_DIR/conf.d/"

# install postgree extension
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        && docker-php-ext-install pdo_pgsql

# Copy PHP-FPM configuration
COPY ./docker/php-fpm/php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
RUN mkdir -p /var/run/php \
    && chown -R www-data:www-data /var/run/php

COPY ./docker/php-fpm/run.sh /usr/local/bin/run
RUN chmod a+x /usr/local/bin/run

WORKDIR /var/www

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

FROM base AS dev
ENV RUN_SUPERVISOR=false
# Install Xdebug
RUN touch /usr/local/etc/php/xdebug.log \
    && chmod a+rw /usr/local/etc/php/xdebug.log \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# coverage folder for PHPSTORM
RUN mkdir -p /opt/phpstorm-coverage \
    && chown -R www-data:www-data /opt/phpstorm-coverage

USER www-data

CMD ["run"]

FROM base AS prod

RUN apt-get update && apt-get install supervisor -y
COPY --chown=www-data:www-data . /var/www
COPY docker/php-fpm/supervisor /etc/supervisor
ENV APP_ENV=production
ENV RUN_SUPERVISOR=true
ARG CI_JOB_TOKEN
USER www-data
RUN composer config -- gitlab-token.gitlab.com gitlab-ci-token "${CI_JOB_TOKEN}"
RUN composer install --no-interaction --no-dev --optimize-autoloader --no-progress --no-suggest --no-scripts --prefer-dist \
    && composer clear-cache \
    && rm -rf /var/www/.composer/cache

USER root
CMD ["run"]
