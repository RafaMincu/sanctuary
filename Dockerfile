FROM serversideup/php:8.2-fpm-apache

# Setează folderul de lucru
WORKDIR /var/www/html

# Copiază fișierele proiectului
COPY --chown=www-data:www-data . .

# Instalează dependențele Composer (fără cele de development)
RUN composer install --no-dev --optimize-autoloader

# Schimbă folderul public implicit pentru Apache ca să ruleze Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
CMD sh -c "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"