FROM php:8-fpm-bookworm

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN addgroup --gid ${GID} --system devgroup
RUN adduser --ingroup devgroup --system --home /var/www/html --no-create-home --disabled-password --shell /bin/bash --uid ${UID} developer

RUN apt update
RUN apt install -y tmux vim git wget kitty procps iconv  bash unzip libicu-dev libxml2-dev libonig-dev libcurl4-openssl-dev
RUN apt install -y yarnpkg
RUN docker-php-ext-install intl opcache xml mbstring curl iconv
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN sh -c "$(curl --location https://taskfile.dev/install.sh)" -- -d
COPY --chown=developer:devgroup  ./php/conf.d/* /usr/local/etc/php/conf.d
RUN echo "export PATH=$PATH:/var/www/html/bin" >> /etc/bash.bashrc
RUN echo "alias python=python3" >> /etc/bash.bashrc
RUN echo "alias yarn=yarnpkg" >> /etc/bash.bashrc

RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
RUN mkdir -p /var/www/html/var
RUN mkdir -p /var/www/html/var/log
RUN mkdir -p /var/www/html/var/cache
RUN chmod -R 777 /var/www/html/var
RUN sed -i 's/^user\s=.*/user=developer/g' /usr/local/etc/php-fpm.d/www.conf
RUN sed -i 's/^group\s=.*/group=devgroup;/g' /usr/local/etc/php-fpm.d/www.conf
