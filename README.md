# 📝 Task Manager API

Task Manager API – это RESTful API для управления задачами и пользователями, разработанный на Laravel с использованием PostgreSQL и Docker. 

## 🚀 Возможности
- Создание, редактирование и удаление задач
- Назначение задач пользователям
- Фильтрация и сортировка задач
- Группировка задач по статусу
- Система уведомлений при изменении статуса задач
- Очереди и планировщик заданий

## 🛠️ Установка и запуск (Linux/WSL)

### 📥 1. Клонирование репозитория
```sh
git clone https://github.com/Azazlokus/simple-tasks
cd simple-tasks
```

### 🐳 2. Запуск в Docker


```sh
docker-compose up -d --build
```
### 3. Создание .env
```
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
```

### 📂 4. Выполнение миграций
```sh
docker-compose exec app php artisan migrate --seed
```

### 🎯 5. Запуск очередей
```sh
docker-compose exec app php artisan queue:work
```