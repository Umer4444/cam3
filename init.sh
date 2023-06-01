#!/usr/bin/env bash

cd /root/html/camclients

mysql -h "$PREFIX_MYSQL_PORT_3306_TCP_ADDR" -proot -e 'CREATE DATABASE IF NOT EXISTS camclients; use camclients; CREATE TABLE `user_role` (`id` int(11) NOT NULL AUTO_INCREMENT, `role_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL, `parent_id` int(11) DEFAULT NULL,  PRIMARY KEY (`id`))'

if [ $APPLICATION_ENV = 'development' ]
then

    cp -n config/autoload/dist/settings.local.php.dist config/autoload/settings.local.php
    cp -n config/autoload/dist/settings.global-development.php.dist config/autoload/settings.global-development.php
    cp -n config/autoload/dist/development.config.php.dist config/development.config.php

    composer install

    php public/index.php orm:schema-tool:update --force
    php public/index.php development enable
    php public/index.php data-fixture:import

else

    composer install --no-dev

fi