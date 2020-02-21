#!/bin/bash

LOGFILE="/var/www/dokuwiki/www/cam304/camera/crontab$(date '+%Y%m%d').log"
cur="/var/www/dokuwiki/www/cam/scripts"
PATHCAM="/var/www/dokuwiki/www/cam304/camera"
PATHCAMDT="$PATHCAM/$(date '+%Y%m%d')/record"


if [ ! -d "$PATHCAMDT/web" ]; then
  # Control will enter here if $DIRECTORY doesn't exist.
  echo "$PATHCAMDT/web doesn't exist." >> "$LOGFILE"
  mkdir "$PATHCAMDT/web"
fi

echo "--> Started at $(date) dir $PATHCAMDT." >> "$LOGFILE"
# echo "$PATHCAM/$(date '+%Y%m%d')/record" > "$LOGFILE" 2>&1

cd $PATHCAMDT

 for i in *.avi; do 
  ffmpeg -loglevel error -i  "$i" -c:v libx264 -profile:v main -level 4.0 -preset fast  -movflags +faststart  -c:a aac -b:a 128k -strict -2 "./web/${i%.*}.mp4" && rm $i && echo "$(date +%x_%H:%M:%S:%N) decode $i" >> "$LOGFILE"; 
 done

 echo "==> Stoped at $(date)." >> "$LOGFILE"

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