FROM debian:9

RUN useradd docker-user

RUN apt-get update && \
    apt-get dist-upgrade -y && \
    apt-get install -y \
      apache2 \
      php7.0 \
      php7.0-cli \
      libapache2-mod-php7.0 \
      php-apcu \
      php-xdebug \
      php7.0-gd \
      php7.0-json \
      php7.0-ldap \
      php7.0-mbstring \
      php7.0-mysql \
      php7.0-pgsql \
      php7.0-sqlite3 \
      php7.0-xml \
      php7.0-xsl \
      php7.0-zip \
      php7.0-soap \
php7.0-opcache 

ADD etc/ /etc

RUN rm -rf /var/lock/apache2

RUN a2enmod rewrite

EXPOSE 80

VOLUME [ "/var/www/kult" ]

WORKDIR /var/www/kult

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]

RUN a2dissite 000-default

ADD "./etc/apache2/sites-enabled/kult.conf" "/etc/apache2/sites-enabled/kult.conf" 

#RUN a2ensite kult
RUN service apache2 restart