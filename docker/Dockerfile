# Web server image for flat-webapp: Apache with PHP 7.2 (the version flat's
# php-7.2 phase targets). The application and its dependencies are mounted from
# the working tree at run time; nothing is copied into the image.
FROM php:7.2-apache@sha256:4dc0f0115acf8c2f0df69295ae822e49f5ad5fe849725847f15aa0e5802b55f8
COPY apache.conf /etc/apache2/sites-available/000-default.conf
# PHP configuration: the image ships development and production templates.
# they differ in error_reporting; zz-errors.ini then turns display off in both.
ARG PHP_INI=development
RUN cp "/usr/local/etc/php/php.ini-${PHP_INI}" /usr/local/etc/php/php.ini
COPY zz-errors.ini /usr/local/etc/php/conf.d/zz-errors.ini
RUN a2enmod rewrite headers
WORKDIR /var/www/webapp
