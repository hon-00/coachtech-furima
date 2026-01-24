# coachtech-furima

## アプリ概要
フリマアプリです。商品出品〜購入に加え、取引開始〜取引完了までのチャット機能（画像添付・編集/削除・下書き保存）、未読管理、取引完了後のユーザー評価（平均表示）を実装しています。

---

## 環境構築
- git clone git@github.com:hon-00/coachtech-furima.git
- cd coachtech-furima
- docker-compose up -d --build

## Laravel環境構築
- docker-compose exec php bash
- composer install
- cp .env.example .env
- php artisan key:generate
- chmod -R 775 storage bootstrap/cache
- php artisan storage:link
- php artisan migrate:fresh --seed

### DB設定（Docker用）
.env は .env.example をコピーして使用してください。  
Docker 環境では MySQL コンテナ名をホストとして指定するため DB_HOST=mysql となっています。

---

## ダミーデータ
本アプリでは要件に沿ったダミーデータをSeederで投入します。

### ユーザー
- ダミーユーザーを3名作成します（dummy1〜dummy3）
- うち3人目は商品を所持しません

### 商品
- 商品を10件作成します
- 上5件は1人目に紐付け、残り5件は2人目に紐付けます
- 商品画像は **外部URL** または **storage画像パス** の両方に対応しています

---

## 開発環境確認（主要URL）
- トップページ（商品一覧）: http://localhost/
- 商品詳細: http://localhost/item/{item_id}
- ユーザー登録: http://localhost/register
- マイページ: http://localhost/mypage
- phpMyAdmin: http://localhost:8080/

---

## 使用技術（実行環境）
- PHP 8.1.33
- Laravel 8.83.29
- MySQL 8.0.26
- Nginx 1.21.1
- Composer 2.8.12
- jQuery 3.7.1.min.js
- Docker 23.x

---

## ER図
<img width="2121" height="2101" alt="coachtech_furima：フリマ drawio" src="https://github.com/user-attachments/assets/237c6c2e-6f62-4baf-bc4e-3be4466ca97d" />
