# 📝 Task Manager API

Task Manager API – это RESTful API для управления задачами и пользователями, разработанный на Laravel с использованием PostgreSQL и Docker. 

## 🚀 Возможности
- Создание, редактирование и удаление задач
- Назначение задач пользователям
- Фильтрация и сортировка задач
- Группировка задач по статусу
- Система уведомлений при изменении статуса задач
- Очереди и планировщик заданий

## 🛠️ Установка и запуск

### 📥 1. Клонирование репозитория
```sh
https://github.com/Azazlokus/simple-tasks
cd task-manager
```

### 📌 2. Создание .env файла
```sh
Копировать
Редактировать
cp .env.example .env
php artisan key:generate
```
### 🐳 3. Запуск в Docker

```sh
docker-compose up -d --build
```
### 📂 4. Выполнение миграций
```sh
docker-compose exec app php artisan migrate --seed
```

### 🎯 5. Запуск очередей
```sh
docker-compose exec app php artisan queue:work
```