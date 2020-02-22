#!/bin/bash

cur="/var/www/dokuwiki/www/cam/scripts"

cd $cur

./clean.sh > ./crontab1d.log 2>&1