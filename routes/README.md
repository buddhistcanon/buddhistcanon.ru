## Фонд Канона Буддизма

### Установка

Скопировать .env.example.select в .env

Отредактировать .env

```bash
make up
sail artisan key:generate
sail npm install
make migrate-seed
```
### Разработка

```bash
make vite-dev
```
Открыть http://localhost:7480

### Установка на сервере

meilisearch
```bash
# https://www.meilisearch.com/docs/learn/getting_started/installation

# Add Meilisearch package
echo "deb [trusted=yes] https://apt.fury.io/meilisearch/ /" | sudo tee /etc/apt/sources.list.d/fury.list

# Update APT and install Meilisearch
sudo apt update && sudo apt install meilisearch

# Launch Meilisearch
nohup meilisearch &
```

Альтернатива - запуск в докере https://www.meilisearch.com/docs/learn/cookbooks/docker

```bash
cp .env.example .env
nano .env
composer install
php artisan key:generate
```

### Инициализация

```bash
php artisan migrate --seed
php artisan lb:import_file_suttas --rebuild
php artisan lb:import_theravadaru_suttas --rebuild
```

### Разработка

Запустить веб-сервер:
```bash 
php artisan serve --port=7480
```
Запустить vite-сервер:
```bash
npm run dev
```


### SSR

Запуск ssr-сервера:

```bash
npm run build && node bootstrap/ssr/ssr.mjs npm run ssr-server
```
На сервере должен стоять node.js 14+ (https://github.com/nodesource/distributions#debinstall)

### Импорт сутт

```bash
make import-file-suttas
make import-theravadaru-suttas
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
