###> ARGUMENTS ###
ARG PHP
ARG NAME
###< ARGUMENTS ###

###> PHP IMAGE FORM ARGUMENTS ###
FROM $PHP
###< PHP IMAGE FORM ARGUMENTS ###

###> LOCALE ENV ###
ENV MUSL_LOCPATH="/usr/share/i18n/locales/musl" \
    LC_NUMERIC="ru_RU.UTF-8" \
    LC_TIME="ru_RU.UTF-8" \
    LC_COLLATE="ru_RU.UTF-8" \
    LC_MONETARY="ru_RU.UTF-8" \
    LC_MESSAGES="ru_RU.UTF-8" \
    LC_PAPER="ru_RU.UTF-8" \
    LC_NAME="ru_RU.UTF-8" \
    LC_ADDRESS="ru_RU.UTF-8" \
    LC_TELEPHONE="ru_RU.UTF-8" \
    LC_MEASUREMENT="ru_RU.UTF-8" \
    LC_IDENTIFICATION="ru_RU.UTF-8"
###< LOCALE ENV ###

RUN set -xe \
    ###> BASE UTILS ###
    && apk add --update --no-cache \
        openrc \
        bash \
        vim \
        icu \
        yarn \
    ###> LOCALE EXTENSION ### \
        musl-locales \
        musl-locales-lang \
    ###> DEV EXTENSION ###
        $PHPIZE_DEPS \
        make \
        autoconf \
        g++ \
        icu-dev \
        postgresql-dev \
        rabbitmq-c-dev \
    ###< BASE UTILS ###

    ###> CONFIGURE PHP EXTENSIONS
    && docker-php-ext-configure intl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    ###< CONFIGURE PHP EXTENSIONS

    ###> INSTALL PHP EXTENSIONS
    && docker-php-ext-install -j$(nproc)  \
        intl \
        pdo \
        pgsql \
        pdo_pgsql \
        opcache \
        exif \
    ###< INSTALL PHP EXTENSIONS \

    ###> INSTALL PECL AMQP ###
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    ###< INSTALL PECL AMQP ###

    ###> CACHE CLEAR ### \
    && apk del --no-cache \
        make \
        autoconf \
        g++ \
        icu-dev \
    && rm -rf /tmp/* \
    && find /usr/local/lib -type f -print0 | xargs -0 strip --strip-all 2>/dev/null || true \
    && rm -rf /usr/local/lib/php/doc/* /var/cache/apk/*
    ###< CACHE CLEAR ###

WORKDIR /var/www/html

COPY . /var/www/html

COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

RUN composer i && yarn && yarn build

CMD ["php-fpm"]