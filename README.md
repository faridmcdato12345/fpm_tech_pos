# Inventory Management System and POS with AI PREDICTION

A simple inventory management system and a point of sale with sales AI prediction.

## 🚀 Installation

To get started, clone the repository using:

```sh
git clone https://github.com/faridmcdato12345/fpm_tech_pos.git
cd fpm_tech_pos
```
Then, install required php packages using:

```sh
composer install
```
Then, install node packages using:

```sh
npm install
```
Then, setup the environment variable using:

```sh
cp env.example .env
```
Then, generate application key using:

```sh
php artisan key:generate
```
To run the web app you can type the command:

```sh
php artisan serve
npm run dev
```
php artisan serve is unnecessary if you are using laravel herd, you just have to do `npm run dev` and write the url for example `fpm_tech_pos.test` take note of the `.test` because is very important

# TO MAKE THE AI PREDICTION FEATURE WORK YOU NEED TO INSTALL PYTHON AND PACKAGES TO YOUR LOCAL MACHINE

# INSTALLATION

First, install python by downloading the python installer on the official python website `https://www.python.org/downloads/`

📌 Note: The directory which you installed your python because you need it to put it on the `.env` file

#SAMPLE .env file
```sh
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:lcxdfvbuFs4yxraMCZ6M/FeYYqO/PyXgKWNGonIBwWU=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://pos.test

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
PYTHON_PATH=C:\\Users\\farid\\AppData\\Local\\Programs\\Python\\Python313\\python.exe
```
Second, after installing python you need to install some packages using:

```sh
pip install pandas prophet matplotlib
```

# TO CREATE DATABASE AND ITS TABLE WITH SOME FAKE DATA run the following command:

```sh
php artisan migrate --seed
```

