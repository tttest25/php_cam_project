#!/bin/bash

# File for clean catalog from files older than 28 days

LOGFILE="/var/www/dokuwiki/www/cam304/camera/crontab$(date '+%Y%m%d').log"
cur="/var/www/dokuwiki/www/cam/scripts"
PATHCAM="/var/www/dokuwiki/www/cam304/camera"
 
 
echo "--> Started clean $(date) at dir $PATHCAMDT." >> "$LOGFILE"

cd $PATHCAM
echo " For delete " >> "$LOGFILE"
find $PATHCAM -mtime +28 -print >> "$LOGFILE"
find $PATHCAM -mtime +28 -delete >> "$LOGFILE"

echo "==> Stoped clean at $(date)." >> "$LOGFILE"

# logfile=encodemp4ize.log
# echo "Started at $(date)." > "$logfile"
# rsync -avz --exclude '*.flv' flvs/ mp4s/

# find flvs/ -type f -name '*.flv' -exec sh -c '
# for flvsfile; do
#     file=${flvsfile#flvs/}
#     < /dev/null ffmpeg -i "$flvsfile" -vcodec libx264 -vprofile high \
#         -preset slow -b:v 500k -maxrate 500k -bufsize 1000k \
#         -threads 0 -acodec libfaac -ab 128k \
#         "mp4s/${file%flv}"mp4
#     printf %s\\n "$flvsfile MP4 done." >> "$logfile"
# done
# ' _ {} +