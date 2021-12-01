FROM ubuntu:latest

ENV DEBIAN_FRONTEND=noninteractive
RUN apt update && apt install -y nginx php7.4-fpm php7.4-cli php7.4-curl supervisor

COPY php/ /var/www/php/
COPY php.ini /etc/php/7.4/fpm/

RUN mkdir /var/www/files/
RUN chmod -R 777 /var/www/files
RUN chown -R www-data:www-data /var/www/files/

RUN mkdir -p /var/log/supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /etc/nginx/
# COPY .htpasswd .
COPY nginx.conf .

EXPOSE 8003
STOPSIGNAL SIGTERM

CMD ["/usr/bin/supervisord", "-n"]
