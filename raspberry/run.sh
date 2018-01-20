#!/bin/bash

localUrl=http://localhost/mosquee/fr

if [ -f ~/Desktop/local_site.txt ]; then
    localUrl=`cat ~/Desktop/local_site.txt`
fi

if [ -f ~/Desktop/online_site.txt ]; then  
    onlineUrl=`cat ~/Desktop/online_site.txt`
fi

wget -q --spider http://google.com

if [ $? -eq 0 ]; then
    url=$onlineUrl
else
    url=$localUrl
fi

chromium-browser --app=$url --start-maximized &
sleep 30
xdotool key F11