FROM php:8.1-rc-cli-alpine3.14

LABEL maintainer "Ezra Lazuardy <ezra@exclolab.com>"
LABEL org.opencontainers.image.description "Container Image for Daksysteem Backend."

ARG WWWGROUP=1000

WORKDIR /var/www/html

ENV TZ=UTC
ENV DEBIAN_FRONTEND noninteractive

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN set -eux; \
    chmod +x /usr/local/bin/install-php-extensions \
    && apk --update add --no-cache tzdata gnupg su-exec zip unzip git supervisor sqlite libcap nodejs npm \
    && install-php-extensions pcntl ldap redis intl soap imap pdo_mysql pcov msgpack bcmath igbinary gd zip opcache exif @composer

RUN rm -rf /usr/share/php /usr/share/php8 /usr/src/* /usr/local/bin/phpdbg \
        /usr/local/bin/install-php-extensions /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && setcap "cap_net_bind_service=+ep" /usr/local/bin/php \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories \
    && apk --no-cache add shadow

RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/sh --no-user-group -g $WWWGROUP -u 1337 sail

COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /usr/local/etc/php/conf.d/99-sail.ini
RUN chmod +x /usr/local/bin/start-container

EXPOSE 8000

ENTRYPOINT ["start-container"]
