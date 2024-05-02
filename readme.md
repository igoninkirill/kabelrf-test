# KABEL TEST
## Установка и Запуск
### 1. Клонирование репозитория:

`git clone`

`cd [название папки проекта]`

### 2. Настройка переменных окружения:

`cp .env.example .env`

### 3. Запуск проекта:

`docker-compose up`

`docker compos exec app bash`

`php bin/console doctrine:schema:update --force`

`php bin/console doctrine:fixtures:load`

### 4. Команды

#### Парсинг:
`php bin/console app:parse-products`
