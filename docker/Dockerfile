# Local development web server for flat-webapp. NOT for production.
# Apache with PHP 7.3 (the version flat's php-7.3 phase targets). The application
# and its dependencies are mounted from the working tree at run time; nothing is
# copied into the image.
FROM php:7.3-apache@sha256:b9872cd287ef72bc17d45d713aa2742f3d3bcf2503fea2506fd93aa94995219f
COPY apache.conf /etc/apache2/sites-available/000-default.conf
# PHP configuration for local development: PHP's development template, then
# zz-errors.ini (display off, logging on). This image is NOT meant for production.
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
COPY zz-errors.ini /usr/local/etc/php/conf.d/zz-errors.ini
RUN a2enmod rewrite headers
WORKDIR /var/www/webapp
