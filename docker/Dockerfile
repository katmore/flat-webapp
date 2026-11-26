# Local development web server for flat-webapp. NOT for production.
# Apache with PHP 7.4 (the version flat's php-7.4 phase targets). The application
# and its dependencies are mounted from the working tree at run time; nothing is
# copied into the image.
FROM php:7.4-apache@sha256:c9d7e608f73832673479770d66aacc8100011ec751d1905ff63fae3fe2e0ca6d
COPY apache.conf /etc/apache2/sites-available/000-default.conf
# PHP configuration for local development: PHP's development template, then
# zz-errors.ini (display off, logging on). This image is NOT meant for production.
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
COPY zz-errors.ini /usr/local/etc/php/conf.d/zz-errors.ini
RUN a2enmod rewrite headers
WORKDIR /var/www/webapp
