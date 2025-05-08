# Comentor Project

### API backend for CRUD operations on article comments

## Setup:

### 1. Clone repo:
```sh
git clone git@github.com:peekle86/comentor.git
```
### 2. Go to project folder and install composer dependencies:
```shell
cd comentor
composer install
```
### 3. Copy .env.example file to .env
```shell
cp .env.example .env
```
### 4. Configure DB setting in .env file (example for mysql):
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=comentor
DB_USERNAME=root
DB_PASSWORD=
```
### 4. Configure Sentry setting in .env file:
```
SENTRY_LARAVEL_DSN={your dsn from sentry.io}
SENTRY_TRACES_SAMPLE_RATE=1.0
```
### 4. Change queue connection in .env file (if necessary):
```
QUEUE_CONNECTION=database
```
### 5. Run migrations with seeders:
```shell
php artisan migrate --seed
```
### 6. Broadcasting:
- Install broadcasting with building Node dependencies:
```shell
php artisan install:broadcasting
```
- Setup env variables for reverb by:
```shell
php artisan reverb:install
```
- Run reverb server:
```shell
php artisan reverb:start
```
- Details on how to setup Laravel Echo on client side can be found [here](https://laravel.com/docs/12.x/broadcasting#client-reverb)
### 7. Run queue worker:
```shell
php artisan queue:work
```

## API Documentation
- Swagger: Located on */api/documentation* route. Also can be found in */storage/api-docs/api-docs.json*
- Postman Collection: Can be found in */storage/api-docs/postman_collection.json*

## Tests
### You can run tests by:
```shell
php artisan test
```
