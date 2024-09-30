FROM php:8.3-fpm
RUN apt-get update && apt-get install -y nginx libpq-dev git unzip
RUN docker-php-ext-install pdo pdo_pgsql
COPY ./nginx.conf /etc/nginx/nginx.conf
WORKDIR /var/www
COPY . /var/www
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install
CMD service nginx start && php-fpm
