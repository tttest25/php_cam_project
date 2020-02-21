# PHP.Cam Project for showing camera`s alarm 
service for showing cam snapshots

# ChangeLog 
*   20190507 - add  day of the week to caption 
*   20190220 - 
*   20190221 - to recreate videos create h264:acc  - mp4  with ffmpeg :

for decode 
```bash
 for i in *.avi; do ffmpeg -loglevel error -i  "$i" -c:v libx264 -profile:v main -level 4.0 -preset fast  -movflags +faststart  -c:a aac -b:a 128k -strict -2 "./web/${i%.*}.mp4" && rm $i && echo "decode $i"; done
```

crontab cam-script

```bash
SHELL=/bin/bash
PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin
*/5 * * * *   cam304   /var/www/dokuwiki/www/cam/scripts/cron_5m.sh
```

