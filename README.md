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
php artisan serve is unnecessary if you are using laravel herd you just have to do `npm run dev` and write the url for example `fpm_tech_pos.test` take note of the .test because is very important

# TO MAKE THE AI PREDICTION FEATURE WORK YOU NEED TO INSTALL PYTHON AND PACKAGES TO YOUR LOCAL MACHINE

# INSTALLATION

First, install python by downloading the python installer on the official python website `https://www.python.org/downloads/`

Second, after installing python you need to install some packages using:

```sh
pip install pandas prophet matplotlib
```

# ![image](https://github.com/user-attachments/assets/d4bc45ba-8277-41cb-bc3b-8580b74b118a)
TO CREATE DATABASE AND ITS TABLE WITH SOME FAKE DATA run the following command:

```sh
php artisan migrate --seed
```

