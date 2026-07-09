# 🚨 KIN ALERT

SaaS communautaire de signalement d'incidents.

## Stack

- Laravel 11
- MySQL 8
- Docker
- Sanctum
- Event Driven Architecture
- Queue (Database)
- PHPUnit

## Installation

```bash
docker-compose up -d --build
docker exec -it kin_alert_app bash
composer install
php artisan migrate