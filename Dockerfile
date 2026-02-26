FROM php:8.3-apache


ARG HOST_UID=1000
ARG HOST_GID=1000


RUN apt-get update && apt-get install -y \
    && usermod -u ${HOST_UID} www-data \
    && groupmod -g ${HOST_GID} www-data \
    && chown -R www-data:www-data /var/www/html

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libicu-dev \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libxpm-dev

# Limpa o cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm
RUN docker-php-ext-install \
    intl \
    mbstring \
    pdo \
    pdo_mysql \
    mysqli \
    xml \
    zip \
    gd \
    opcache \
    sockets

# Instala XDebug
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

COPY custom-mysql.cnf /etc/mysql/conf.d/

# Configura Apache
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY apache-custom.conf /etc/apache2/conf-available/custom.conf
RUN a2enconf custom



# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
EXPOSE 80

USER www-data
