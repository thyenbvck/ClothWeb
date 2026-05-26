# ── Base image: PHP 8.2 + Apache ──────────────────────────
FROM php:8.2-apache

# ── PHP extensions ─────────────────────────────────────────
RUN docker-php-ext-install mysqli pdo pdo_mysql

# ── Apache modules ─────────────────────────────────────────
# Remove conflicting MPM symlinks directly, keep only prefork
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf \
          /etc/apache2/mods-enabled/mpm_event.load \
          /etc/apache2/mods-enabled/mpm_worker.conf \
          /etc/apache2/mods-enabled/mpm_worker.load \
    && a2enmod mpm_prefork rewrite deflate expires headers

# ── Allow .htaccess overrides ──────────────────────────────
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# ── Copy application files ─────────────────────────────────
COPY . /var/www/html/

# Remove default Apache placeholder
RUN rm -f /var/www/html/index.html

# ── File permissions ───────────────────────────────────────
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/ \
    && chmod -R 775 /var/www/html/public/img/ \
    && chmod -R 775 /var/www/html/public/images/

# ── Start: let Railway's $PORT override Apache's port 80 ───
EXPOSE 80
CMD bash -c '\
    PORT=${PORT:-80}; \
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf; \
    sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-enabled/000-default.conf; \
    apache2-foreground'
