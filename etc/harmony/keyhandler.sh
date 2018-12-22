#!/bin/bash
#(on|off|reboot|sleep|screen|alarm|www)

. utils

case "$1" in
    ON)
        xset -dpms
        xset +dmps
    ;;
    OFF)
        cmd gksu shutdown -P now
    ;;
    REBOOT)
        cmd gksu shutdown -r now
    ;;
    SLEEP)
        cmd sleep.until
    ;;
    SCREEN)
        cmd screen.toggle
    ;;
    ALARM)
        echo "poop"
    ;;
    WWW)
        cmd which firefox || exit
        if ! cmd pidof firefox; then
            cmd firefox &
            while ! cmd pidof firefox; do
                sleep 1
            done
        fi
        cmd xdotool  windowactivate `xdotool search --name "Mozilla Firefox"`
    ;;
esac
exit 0

echo "$1"