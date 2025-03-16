@echo off
SETLOCAL

:: Проверяем существование .env
IF EXIST .env (
    echo .env файл уже существует. Операция отменена.
    exit /b 1
)

:: Копируем .env.example в .env
copy .env.example .env

:: Генерируем APP_KEY
FOR /F "delims=" %%i IN ('php artisan key:generate --show') DO SET APP_KEY=%%i

:: Обновляем APP_KEY в .env
powershell -Command "(Get-Content .env) -replace 'APP_KEY=', 'APP_KEY=%APP_KEY%' | Set-Content .env"

echo .env файл успешно создан с новым APP_KEY!