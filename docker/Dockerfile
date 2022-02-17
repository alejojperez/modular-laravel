FROM php:8.1-alpine

COPY . /usr/src/app
WORKDIR /usr/src/app

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

CMD [ "php", "./docker/entry-point.php" ]
