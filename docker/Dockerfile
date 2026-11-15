# Web server image for flat-webapp: Apache with PHP 7.2 (the version flat's
# php-7.2 phase targets). The application and its dependencies are mounted from
# the working tree at run time; nothing is copied into the image.
FROM php:7.2-apache@sha256:4dc0f0115acf8c2f0df69295ae822e49f5ad5fe849725847f15aa0e5802b55f8
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite headers
WORKDIR /var/www/webapp
