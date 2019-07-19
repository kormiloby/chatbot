FROM php:7.2-fpm

RUN echo deb http://mirror.yandex.ru/debian/ stable main contrib non-free > /etc/apt/sources.list

RUN apt-get update
RUN apt-get install -y \
        # libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libcurl3-dev \
        libgmp-dev \
        git \
        zip \
        unzip \
        # --with-freetype-dir=/usr/include/
    && docker-php-ext-configure gd --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install -j$(nproc) mbstring tokenizer mysqli pdo_mysql exif curl gmp

#####################################
# Composer:
#####################################

# Install composer and add its bin to the PATH.
RUN curl -s http://getcomposer.org/installer | php && \
    echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc && \
    mv composer.phar /usr/local/bin/composer

#####################################
# ImageMagick:
#####################################
RUN apt-get install -y libmagickwand-dev imagemagick && pecl install imagick && docker-php-ext-enable imagick

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#
COPY .docker/php-fpm/laravel.ini /usr/local/etc/php/conf.d
# Copy xdebug configuration for remote debugging
COPY .docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN usermod -u 1000 www-data
WORKDIR /var/www

COPY composer.lock .
COPY composer.json .
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN php /usr/local/bin/composer install --prefer-dist --no-autoloader --no-scripts

COPY . .
RUN php /usr/local/bin/composer dump-autoload
RUN chown -R www-data: ./storage

#####################################
# xDebug:
#####################################
ARG INSTALL_XDEBUG=false

RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    pecl install xdebug && docker-php-ext-enable xdebug \
    ;fi

# Copy main script
COPY run.sh .

ENTRYPOINT ["./run.sh"]

EXPOSE 9000
