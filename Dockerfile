FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive
RUN apt update && apt install -y nginx php7.4-fpm php7.4-cli php7.4-curl php7.4-zip supervisor

COPY php/ /var/www/php/
COPY php.ini /etc/php/7.4/fpm/
COPY php.ini /etc/php/7.4/cli/
COPY www.conf /etc/php/7.4/fpm/pool.d/

RUN mkdir /var/www/void/
RUN chmod -R 777 /var/www/void
RUN chown -R www-data:www-data /var/www/void/

RUN mkdir -p /var/log/supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /etc/nginx/
COPY nginx.conf .

# Testing 
# ENV DOCKER_URL=172.17.0.1:2375
COPY .htpasswd ./htpasswd/
COPY files/* /var/www/void/templates/

EXPOSE 8003
STOPSIGNAL SIGTERM

CMD ["/usr/bin/supervisord", "-n"]
