FROM php:8.2-fpm-alpine
ARG UID
ARG GID
ENV UID=${UID}
ENV GID=${GID}
RUN addgroup -g ${GID} -S devgroup
RUN adduser --ingroup devgroup --system --disabled-password --shell /bin/sh --uid ${UID} developer
RUN apk update
RUN apk add tmux vim procps iputils git dhclient net-tools nginx composer bash util-linux kitty starship \
icu-dev libxml2-dev
RUN docker-php-ext-install intl opcache xml
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
COPY ./nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./nginx/default.conf /etc/nginx/http.d/default.conf
COPY ./entrypoint /usr/sbin/entrypoint
RUN chmod +x /usr/sbin/entrypoint
COPY ./bashrc /etc/profile.d/30starship.sh
RUN  cat /etc/profile.d/30starship.sh >> /etc/bash/bashrc 
COPY ./php/conf.d/* /usr/local/etc/php/conf.d
COPY ./php/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf
RUN adduser nginx devgroup
RUN adduser www-data devgroup
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
RUN mkdir -p /var/www/html/var
RUN mkdir -p /var/www/html/var/log
RUN mkdir -p /var/www/html/var/cache
RUN chmod -R 777 /var/www/html/var
RUN chown -R developer:devgroup /var/www/html
RUN find /var/www/html -type d -print0 | xargs chmod 777
RUN find /var/www/html -type f -print0 | xargs chmod 666
ENTRYPOINT [ "/usr/sbin/entrypoint" ]