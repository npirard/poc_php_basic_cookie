FROM php:7.0-apache

COPY conf/apache2.conf /etc/apache2/

CMD apache2-foreground
