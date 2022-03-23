FROM php:7.2-fpm

ENV PHALCON_VERSION=3.4.2

RUN apt-get update
RUN apt-get install -y  \
    libssl-dev \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    libevent-dev \
    libmagickwand-dev
RUN docker-php-ext-install -j$(nproc) zip opcache pdo pdo_pgsql
RUN pecl install imagick && docker-php-ext-enable imagick

RUN curl -sSL "https://codeload.github.com/phalcon/cphalcon/tar.gz/v${PHALCON_VERSION}" | tar -xz \
    && cd cphalcon-${PHALCON_VERSION}/build \
    && ./install \
    && cp ../tests/_ci/phalcon.ini $(php-config --configure-options | grep -o "with-config-file-scan-dir=\([^ ]*\)" | awk -F'=' '{print $2}') \
    && cd ../../ \
    && rm -r cphalcon-${PHALCON_VERSION}

RUN mkdir /tmp/php-opcache && chmod -R 777 /tmp/php-opcache
RUN rm /usr/local/etc/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf.default
COPY ./build/php/config/php.ini /usr/local/etc/php
COPY ./build/php/config/www.conf /usr/local/etc/php-fpm.d

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
update-ca-certificates

WORKDIR /opt/app

RUN useradd --create-home --uid 1000 --user-group --system app
USER app

COPY --chown=app:app ./build/php/entrypoint.sh /home/app/entrypoint.sh

CMD /home/app/entrypoint.sh
