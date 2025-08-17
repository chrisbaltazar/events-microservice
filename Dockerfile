FROM laravelsail/php83-composer

WORKDIR /var/www

COPY . .
COPY entrypoint.sh /tmp

RUN chmod +x /tmp/entrypoint.sh
RUN cp .env.example .env
RUN rm -rf storage/logs/*
RUN touch database/database.sqlite
RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000

ENTRYPOINT ["/tmp/entrypoint.sh"]
