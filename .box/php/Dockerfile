FROM php:7.4-fpm

RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections
RUN echo "resolvconf resolvconf/linkify-resolvconf boolean false" | debconf-set-selections

RUN apt-get -qq update && apt-get -y upgrade && apt-get install --no-install-recommends -y \
        curl \
        wget \
        git \
        g++ \
        unzip \
        gnupg \
        apt-utils \
        debconf-utils \
        dialog \
        libmcrypt-dev \
        zlib1g-dev \
        libldap2-dev \
        libxml2-dev \
        zlib1g-dev \
        libicu-dev \
        libmagickwand-dev \
        freetds-dev \
        libssl-dev \
        libaio1 \
        openssl \
        libzip-dev \
        zip \
        librabbitmq-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libpng-dev


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --install-dir=/usr/local/bin \
        --filename=composer
ENV PATH=$PATH:/root/.composer/vendor/bin

# Install PHP extensions
RUN docker-php-ext-configure gd  --with-freetype --with-jpeg \
    && pecl install imagick \
    && pecl install redis \
    && pecl install amqp \
    && docker-php-ext-install \
        intl \
        gd \
        mysqli \
        pdo_mysql \
        soap \
        sockets \
        zip \
        pcntl \
        opcache \
        exif \
    && docker-php-ext-enable \
        imagick \
        redis \
        opcache \
        amqp

# Clean repository
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*
