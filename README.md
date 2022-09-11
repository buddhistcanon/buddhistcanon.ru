## Фонд Канона Буддизма


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

### SSR

Запуск ssr-сервера:

```bash
make vite-build && make ssr-server
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
