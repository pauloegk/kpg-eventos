FROM ambientum/php:7.3-nginx

WORKDIR /var/www/app

ADD ./ /var/www/app

RUN composer install

RUN composer update

EXPOSE 8080
EXPOSE 80
