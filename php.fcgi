#!/bin/bash

PHP_INI_SCAN_DIR=/home/shoppingbulg/.sh.phpmanager/php73.d
export PHP_INI_SCAN_DIR

DEFAULTPHPINI=/home/shoppingbulg/public_html/espravki.com/php73-fcgi.ini
exec /opt/cpanel/ea-php73/root/usr/bin/php-cgi -c ${DEFAULTPHPINI}
