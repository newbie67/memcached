FROM php:7.4-cli

WORKDIR /app

RUN apt-get update \
    && apt-get install -y telnet git

RUN docker-php-ext-install sockets

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#CMD [ "php", "-sS", "0.0.0.0:80"]#
CMD [ "sleep", "999999999"]