# laravel-reversi-app

### 環境構築
下記の手順で開発環境を立ち上げる

- Docker Desktopをインストールする

https://www.docker.com/products/docker-desktop

- 任意のディレクトリにcloneする
```php
git clone git@github.com:ts1982/laravel-reversi-app.git
```

- laravel-reversi-appに移動する
```php
cd laravel-reversi-app
```

- dockerを起動する
```php
docker compose up -d
```

```php
docker compose exec app bash
```

- docker内でLaravelの設定を行う
```php
cd server
```

```php
cp .env.example .env
```

```php
composer install
```

```php
php artisan key:generate
```

```php
php artisan migrate
```

```php
exit
```
- Reactを起動する
```php
cd front
```

```php
npm install
```

```php
npm start
```

http://localhost:3000 にアクセスする
