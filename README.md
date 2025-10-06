
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://img.shields.io/badge/PHP-8.4-blue" alt="PHP"></a>
<a href="https://img.shields.io/badge/Laravel-11.x-red" alt="Laravel"></a>
</p>

# Laravel Clients API

API REST en Laravel que implementa el **CRUD de la tabla `clients`** con capas de **Controller → Service → Repository**.

## 🚀 Requisitos
- PHP 8.2+ (tú tienes 8.4 ✔)
- Composer 2.x
- MySQL 8.x (Server + cliente en PATH)
- Extensiones PHP: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo`, `zip`
- (Opcional) Postman o Insomnia

## 🛠️ Instalación y arranque

```bash
# 1) Copiar variables de entorno
cp .env.example .env

# 2) Configurar BD en .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel_api
# DB_USERNAME=tu_usuario
# DB_PASSWORD=tu_password

# 3) Instalar dependencias
composer install

# 4) Generar APP_KEY
php artisan key:generate

# 5) Ejecutar migraciones
php artisan migrate

# 6) Arrancar el servidor
php artisan serve
# -> http://127.0.0.1:8000
