#!/usr/bin/env bash
sleep 10;
php /app/bin/console messenger:consume async_high async_normal failed >> /var/log/queue.log 2>&1
