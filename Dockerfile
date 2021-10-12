FROM ubuntu:latest

ENV DEBIAN_FRONTEND=noninteractive
RUN apt update && apt install -y net-tools vim apache2-utils build-essential libpcre3 libpcre3-dev zlib1g zlib1g-dev libssl-dev libgd-dev libxml2 libxml2-dev uuid-dev wget

RUN mkdir /nginx
RUN wget "http://nginx.org/download/nginx-1.18.0.tar.gz"
RUN wget "https://codeload.github.com/masterzen/nginx-upload-progress-module/legacy.tar.gz/v0.8.4"
RUN tar -zxvf nginx-1.18.0.tar.gz -C ./nginx
RUN tar -zxvf v0.8.4 -C ./nginx

RUN mkdir /etc/nginx

WORKDIR /nginx/nginx-1.18.0/
RUN ./configure --prefix=/etc/nginx \
  --sbin-path=/usr/sbin/nginx \
  --conf-path=/etc/nginx/nginx.conf \
  --http-log-path=/var/log/nginx/access.log \
  --error-log-path=/var/log/nginx/error.log \
  --with-pcre  \
  --lock-path=/var/lock/nginx.lock \
  --pid-path=/var/run/nginx.pid \
  --with-http_ssl_module \
  --with-http_image_filter_module=dynamic \
  --modules-path=/etc/nginx/modules \
  --with-http_v2_module \
  --with-stream=dynamic \
  --with-http_addition_module \
  --with-http_mp4_module \
  --add-module=/nginx/masterzen-nginx-upload-progress-module-82b35fc/
RUN make && make install

WORKDIR /etc/nginx/
COPY .htpasswd .
COPY nginx.conf .
COPY site-available/* site-available/
RUN nginx

# RUN mkdir -p /var/www/var25.com.ua/html/www/test

# COPY https://mortis-grimreaper.ddns.net/projects/Projects/ /etc/nginx/sites-available/

# RUN ln -s /etc/nginx/sites-available/var25.com.ua /etc/nginx/sites-enabled/
# RUN htpasswd -b -c /etc/nginx/.htpasswd user user
