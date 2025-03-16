#!/bin/bash

if [ -f .env ]; then
    echo ".env файл уже существует. Операция отменена."
    exit 1
fi

cp .env.example .env

APP_KEY=$(php artisan key:generate --show)

sed -i "s|APP_KEY=|APP_KEY=${APP_KEY}|" .env

echo ".env файл успешно создан с новым APP_KEY!"