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
    LC_IDENTIFICATION="ru_RU.UTF-8" \
    TZ="Europe/Moscow"
###< LOCALE ENV ###

RUN set -xe \
    ###> BASE UTILS ###
    && apk add --update --no-cache \
        openrc \
        bash \
        vim \
        icu \
        yarn \
        tzdata \
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
        supervisor \
    ###< BASE UTILS ###
    \
    ###> CONFIGURE PHP EXTENSIONS
    && docker-php-ext-configure intl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    ###< CONFIGURE PHP EXTENSIONS
    \
    ###> INSTALL PHP EXTENSIONS
    && docker-php-ext-install -j$(nproc)  \
        intl \
        pdo \
        pgsql \
        pdo_pgsql \
        opcache \
        exif \
    ###< INSTALL PHP EXTENSIONS \
    \
    ###> INSTALL PECL AMQP ###
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    ###< INSTALL PECL AMQP ###
    \
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

USER www-data

COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY --chown=www-data:www-data composer.json composer.lock package.json yarn.lock ./

RUN composer install --no-scripts --no-dev --optimize-autoloader

RUN yarn install

COPY --chown=www-data:www-data . .

RUN composer run-script post-install-cmd

RUN yarn build

CMD ["supervisord"]
