FROM php:8.0-fpm

WORKDIR /app

# APT-GET installs
RUN apt-get update && \
    export DEBIAN_FRONTEND=noninteractive && \
    apt-get install -y --no-install-recommends \
    apt-utils \
    curl \
    git \
    htop \
    libicu-dev \
    libcurl4-gnutls-dev \
    libmcrypt-dev \
    libpng-dev \
    libssl-dev \
    libxml2-dev \
    memcached \
    openssl \
    supervisor \
    tcpdump \
    telnet \
    unzip \
    vim \
    wget && \
    rm -rf /var/lib/apt/lists/*

# PHP Extensions
RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd
RUN docker-php-ext-configure mysqli --with-mysqli=mysqlnd

RUN docker-php-ext-install \
    bcmath \
    gd \
    opcache \
    pcntl \
    pdo_mysql \
    soap

# Extensions by PECL
RUN pecl install apcu && docker-php-ext-enable apcu && \
    pecl install redis && docker-php-ext-enable redis && \
    pecl install xdebug && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# PHP Configuration
RUN echo "error_reporting=E_ALL && ~E_DEPRECATED" >> /usr/local/etc/php/php.ini && \
    echo "display_errors=1" >> /usr/local/etc/php/php.ini && \
    echo "error_log=/var/log/php-error.log" >> /usr/local/etc/php/php.ini && \
    echo "max_execution_time=0" >> /usr/local/etc/php/php.ini && \
    echo "max_input_time=-1" >> /usr/local/etc/php/php.ini && \
    echo "memory_limit=4G" >> /usr/local/etc/php/php.ini && \
    echo "date.timezone=UTC" >> /usr/local/etc/php/conf.d/timezone.ini && \
    echo "upload_max_filesize=50M" >> /usr/local/etc/php/conf.d/max_size.ini && \
    echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.validate_timestamps=1" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.max_accelerated_files=15000" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.max_wasted_percentage=10" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.interned_strings_buffer=16" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.enable_file_override=1" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.error_log=/var/log/php-opcache-error.log" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "extension=apcu.so" >> /usr/local/etc/php/conf.d/apcu.ini && \
    echo "apc.enable_cli=1" >> /usr/local/etc/php/conf.d/apcu.ini && \
    echo "apc.enable=1" >> /usr/local/etc/php/conf.d/apcu.ini && \
    echo "extension=redis.so" >> /usr/local/etc/php/conf.d/redis.ini && \
    echo "[XDebug]" > /usr/local/etc/php/conf.d/xdebug.ini \
    echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.client_host=172.17.0.1" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.client_port=9126" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.idekey=wiley" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.output_dir=/app/data/profiler" >> /usr/local/etc/php/conf.d/xdebug.ini

# Directories
RUN mkdir -p /tmp/wiley && chmod 777 -R -f /tmp/wiley
RUN mkdir -p /tmp/wiley/cache && chmod 777 -R -f /tmp/wiley/cache

EXPOSE 9000

CMD ["php-fpm"]
