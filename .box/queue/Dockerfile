FROM php:7.4-fpm

RUN apt-get -qq update && apt-get -y upgrade && apt-get install --no-install-recommends -y \
        curl \
        wget \
        git \
        g++ \
        unzip \
        gnupg \
        apt-utils \
        debconf-utils \
        dialog

RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections
RUN echo "resolvconf resolvconf/linkify-resolvconf boolean false" | debconf-set-selections

# Install PHP extensions deps
RUN apt-get install --no-install-recommends -y \
        libmcrypt-dev \
        zlib1g-dev \
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
        libpq-dev \
        librabbitmq-dev

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --install-dir=/usr/local/bin \
        --filename=composer
ENV PATH=$PATH:/root/.composer/vendor/bin

# Install PHP extensions
RUN pecl install imagick \
    && pecl install redis \
    && pecl install amqp \
    && docker-php-ext-install \
        intl \
        mysqli \
        pdo_mysql \
        soap \
        sockets \
        zip \
        pcntl \
        opcache \
        exif \
        pdo_pgsql \
    && docker-php-ext-enable \
        imagick \
        redis \
        opcache \
        amqp

# Clean repository
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

#Create the log file
RUN touch /var/log/queue.log
RUN chmod 0777 /var/log/queue.log


RUN mkdir -p /scripts
ADD entrypoint.sh /scripts/entrypoint.sh

CMD ["bash", "/scripts/entrypoint.sh"]
