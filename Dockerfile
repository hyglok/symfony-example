FROM php:7.4-fpm-alpine

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
ENV PRE_BUILD_PACKAGES="acl curl hiredis icu oniguruma postgresql-client grep gmp libmcrypt freetype libxpm libwebp libjpeg-turbo libjpeg bzip2 openssl krb5 libxml2 tar make autoconf libbz2" \
TEMP_PACKAGES="build-base pcre-dev icu-dev oniguruma-dev curl-dev wget libressl-dev postgresql-dev gmp-dev libmcrypt-dev freetype-dev libxpm-dev libwebp-dev libjpeg-turbo-dev bzip2-dev krb5-dev libxml2-dev"

RUN apk --update add $TEMP_PACKAGES && \
       apk --update add $PRE_BUILD_PACKAGES && \
       pecl install redis-5.2.2 &&\
       docker-php-ext-enable redis &&\
       docker-php-ext-install -j$(nproc) bcmath mbstring pdo pdo_pgsql exif sockets gd gmp intl