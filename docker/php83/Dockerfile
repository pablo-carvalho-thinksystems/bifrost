FROM php:8.3.0-fpm-alpine

RUN apk update

ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apk add --no-cache \
    bzip2-dev \
    ca-certificates \
    curl \
    fcgi \
    freetype-dev \
    gettext-dev \
    gnu-libiconv \
    icu-dev \
    imagemagick \
    imagemagick-dev \
    libjpeg-turbo-dev \
    libmcrypt-dev \
    libpng \
    libpng-dev \
    libressl-dev \
    libtool \
    libwebp-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    unzip \
    linux-headers \
    autoconf \
    build-base \
    nodejs \
    npm

RUN docker-php-ext-install \
    bcmath \
    bz2 \
    exif \
    ftp \
    gettext \
    gd \
    intl \
    mbstring \
    opcache \
    pdo \
    pdo_mysql \
    shmop \
    sockets \
    sysvmsg \
    sysvsem \
    sysvshm \
    zip

RUN pecl install redis && docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY docker-php.ini /usr/local/etc/php/conf.d/
COPY docker-php-fpm.conf /usr/local/etc/php-fpm.d/
COPY xdebug.ini ${PHP_INI_DIR}/conf.d

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
