FROM alpine:edge

LABEL Maintainer="Tim de Pater <code@trafex.nl>" \
      Description="Lightweight container with Nginx 1.18 & PHP-FPM 7.3 based on Alpine Linux."

ENV ELASTICSEARCH_URL=$ELASTICSEARCH_URL
ENV ELASTICSEARCH_USERNAME=$ELASTICSEARCH_USERNAME
ENV ELASTICSEARCH_PASSWORD=$ELASTICSEARCH_PASSWORD

ENV EMAIL=$EMAIL
ENV ENCODED_PASSWORD=$ENCODED_PASSWORD

# Install packages and remove default server definition
RUN apk --update add php7 php7-fpm php7-opcache php7-json php7-openssl php7-curl \
    php7-zlib php7-xml php7-simplexml php7-phar php7-intl php7-dom php7-xmlreader php7-ctype php7-session \
    php7-tokenizer php7-pdo php7-pdo_mysql php7-pdo_pgsql php7-iconv php7-zip \
    php7-mbstring nginx supervisor nodejs nodejs npm curl && \
    rm /etc/nginx/conf.d/default.conf

# Configure nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY docker/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY docker/php.ini /etc/php7/conf.d/custom.ini

# Configure supervisord
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create folders
RUN mkdir -p /var/www/html
RUN mkdir -p /.composer
RUN mkdir -p /.npm

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html && \
  chown -R nobody.nobody /.composer && \
  chown -R nobody.nobody /.npm && \
  chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/log/nginx

# Switch to use a non-root user from here on
USER nobody

# Add application
WORKDIR /var/www/html
COPY --chown=nobody . /var/www/html/

# Install composer from the official image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Run composer install to install the dependencies
RUN composer install --optimize-autoloader --no-interaction --no-progress

RUN npm install --silent
RUN npm run --silent build

COPY --chown=nobody .env.dist .env

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
