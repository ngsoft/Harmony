#!/bin/bash
#(on|off|reboot|sleep|screen|alarm|www)

. utils

case "$1" in
    on)
        echo "case 1"
    ;;
    off)
        echo "case 2 or 3"
    ;;
    reboot)
        echo "poop"
    ;;
    sleep)
        echo "poop"
    ;;
    screen)
        echo "poop"
    ;;
    alarm)
        echo "poop"
    ;;
    www)
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