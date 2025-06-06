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
        yarn \
        icu \
        tzdata \
    ###> LOCALE EXTENSION ### \
        musl-locales \
        musl-locales-lang \
    ###> DEV EXTENSION ### \
        $PHPIZE_DEPS \
        linux-headers \
        make \
        autoconf \
        g++ \
        icu-dev \
        postgresql-dev \
        rabbitmq-c-dev \
    ###< BASE UTILS ###
    \
    ###> CONFIGURE PHP EXTENSIONS ###
    && docker-php-ext-configure intl \
    && docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql \
    ###< CONFIGURE PHP EXTENSIONS ###
    \
    ###> INSTALL PHP EXTENSIONS ###
    && docker-php-ext-install -j$(nproc) \
        intl \
        pdo \
        pgsql \
        pdo_pgsql \
        opcache \
        exif \
        bcmath \
    ###< INSTALL PHP EXTENSIONS ###
    \
    ###> INSTALL PECL AMQP ###
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    ###< INSTALL PECL AMQP ###
    \
    ###> INSTALL PECL XDEBUG ###
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    ###< INSTALL PECL XDEBUG ###
    \
    ###> CLEAN UP ###
    && apk del --no-cache --purge \
        make \
        autoconf \
        g++ \
        icu-dev \
    && rm -rf /tmp/* \
    && find /usr/local/lib -type f -print0 | xargs -0 strip --strip-all 2>/dev/null || true \
    && rm -rf /usr/local/lib/php/doc/* /var/cache/apk/*
    ###< CLEAN UP ###

COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

CMD ["php-fpm"]