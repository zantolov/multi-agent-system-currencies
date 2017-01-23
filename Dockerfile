FROM ubuntu:latest

RUN apt-get update --fix-missing

RUN apt-get install -y software-properties-common

RUN add-apt-repository -y ppa:ondrej/php

RUN apt-get update
RUN apt-key update

RUN apt-get -y --allow-unauthenticated install php7.0 php7.0-fpm php-gearman

RUN apt-get -y --allow-unauthenticated install php7.0-curl php7.0-json php7.0-mcrypt php7.0-xml

RUN mkdir -p /var/app

VOLUME /var/app