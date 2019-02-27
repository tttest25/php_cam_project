#!/bin/bash

cur="/var/www/dokuwiki/www/cam/scripts"

cd $cur

./decode_video.sh > ./crontab.log 2>&1