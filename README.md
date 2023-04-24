## Фонд Канона Буддизма

### Установка

Скопировать .env.example в .env 

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
sudo echo "deb [trusted=yes] https://apt.fury.io/meilisearch/ /" > /etc/apt/sources.list.d/fury.list
sudo apt update && sudo apt install meilisearch-http
nohup meilisearch & 
```

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
make vite-build && make ssr-server
```

### Импорт сутт

```bash
make import-file-suttas
make import-theravadaru-suttas
```
 
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
