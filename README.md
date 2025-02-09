# Фонд Канона Буддизма

## Установка с использованием Docker

Установить пакетный менеджер composer.

Установить зависимости php:

```bash
composer install --ignore-platform-reqs
```

Скопировать `.env.docker` в `.env` , отредактировать если нужно (первый блок со списком портов, открытых в систему -
если вдруг некоторые из них у вас используются, но скорее всего нет)

Запуск докер-контейнеров:

```bash
make up
```

Обновить зависимости js:

```bash
make npm-install
```

#### Инициализация при первом запуске проекта

```bash
make prepare
```

#### Запуск

Компиляция фронтенда:

```bash
make vite-dev
```

Сайт открывается по адресу http://localhost:7400

## Установка без использования Docker

Установить PHP 8.1, MySQL 5.7+, NodeJS 14+ тем способом, который принят в вашей системе.

Установить и запустить Meilisearch (https://www.meilisearch.com/docs/learn/getting_started/installation)

Скопировать `.env.native` в `.env` , отредактировать если нужно.

#### Инициализация при первом запуске проекта

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --seed
php artisan ide-helper:models -W -R
```

#### Запуск

Запустить веб-сервер:

```bash 
php artisan serve --port=7400
```

Запустить vite-сервер:

```bash
npm run dev
```

Сайт открывается по адресу http://localhost:7400

## Разработка

### Консольные команды

Импорт сутт из файлов (оригинал на пали и перевод на английский от Бханте Суджато):

```bash
sail artisan lb:import_file_suttas MN --rebuild
sail artisan lb:import_file_suttas AN --subfolders=10 --rebuild
sail artisan lb:import_file_suttas SN --subfolders=57 --rebuild
```

Импорт сутт с сайта theravada.ru

```bash
make import-theravadaru-suttas
или
php artisan lb:import_theravadaru_suttas AN --rebuild
``` 

### CSS

Используем [tailwindcss](https://tailwindcomponents.com/cheatsheet/).

### Troubleshooting

#### Поиск не работает

```
make reindex

или

php artisan lg:reindex
```

#### Несоответствие версий БД meilisearch

Если обнаруживается несоответствие версий БД meilisearch, то проще удалить контейнер и вольюм, и пересоздать контейнер:

```bash
docker container rm buddhistcanon-meilisearch-1
docker volume rm buddhistcanon_sail-meilisearch
docker compose up -d
make reindex
```

### Integration testings

Install Google Chrome webdriver

```
php artisan dusk:install
```

Refresh database if necessary

```
make migrate-refresh-seed
make add-test-users
```

Run tests

```
source .env.dusk.sh && php artisan dusk
```

## SSR

Запуск ssr-сервера на сервере:

```bash
make server-ssr
```

На сервере должен стоять node.js 14+

Запуск поискового движка meilisearch:

```bash
make server-meilisearch
```

## Импорт сутт

Репозиторий Павла Каторгина с построчным сведением переводов SV c палийским
оригиналом: https://github.com/o28o/fdg/tree/main/assets/texts/sutta

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


