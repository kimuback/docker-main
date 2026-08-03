FROM php:8.1-apache
RUN docker-php-ext-install mysqli
COPY html/ /var/www/html/
RUN mkdir -p /var/www/data && chmod 777 /var/www/data
EXPOSE 80