# Use PHP 8.3.8 CLI base image
FROM php:8.3.8-cli

WORKDIR /var/www

RUN apt-get update && \
    apt-get install -y git libzip-dev nano && \
    docker-php-ext-install zip pdo_mysql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); exit(1); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

COPY . /var/www

RUN composer require laravel/socialite && \
    composer install --no-scripts --no-autoloader && \
    composer dump-autoload --optimize

#RUN php artisan migrate --force

EXPOSE 8000

ENTRYPOINT ["php", "artisan", "serve"]
CMD ["--host=0.0.0.0"]
