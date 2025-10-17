# coachtech-furima

## 環境構築
- git clone git@github.com:hon-00/coachtech-furima.git
cd coachtech-furima
- docker-compose up -d --build

## Laravel環境構築
- docker-compose exec php bash
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate:fresh --seed

## 開発環境確認
- トップページ: http://localhost/
- ユーザー登録: http://localhost/register
- phpMyAdmin: http://localhost:8080/

## 使用技術（実行環境）
- PHP 8.1.33
- Laravel 8.83.29
- MySQL 8.0.26
- Nginx 1.21.1
- Composer 2.8.12
- jQuery 3.7.1.min.js
- Docker 23.x

## ER図
<img width="1851" height="1371" alt="coachtech_furima drawio" src="https://github.com/user-attachments/assets/1668c5c4-3312-4764-8b3d-b972447db1eb" />
