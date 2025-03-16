#!/bin/bash

# Проверяем, существует ли уже .env
if [ -f ./../../.env ]; then
    echo ".env файл уже существует. Операция отменена."
    exit 1
fi

# Копируем .env.example в .env
cp .env.example ./../../.env

# Генерируем APP_KEY и записываем в .env
APP_KEY=$(php artisan key:generate --show)

# Обновляем значение APP_KEY в .env
sed -i "s|APP_KEY=|APP_KEY=${APP_KEY}|" ./../../.env

echo ".env файл успешно создан с новым APP_KEY!"