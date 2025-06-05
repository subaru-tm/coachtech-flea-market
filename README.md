# Coachtech-flea-market

## 環境構築
- laravelテンプレートのクローン
  - git clone git@github.com:coachtech-material/laravel-docker-template.git
  - mv laravel-docker-template/ coachtech-flea-market
  - cd coachtech-flea-market

- リモートリポジトリの変更
  - git remote set-url origin git@github.com:subaru-tm/coachtech-flea-market.git
  - git remote -v
    - 出力メッセージ
      - origin  git@github.com:subaru-tm/coachtech-flea-market.git (fetch)
      - origin  git@github.com:subaru-tm/coachtech-flea-market.git (push)

  - git add .
  - git commit -m "リモートリポジトリの変更"
  - git push origin main

- dockerの構築
  - docker-compose up -d --build

- laravelインストール
  - docker-compose exec php bash
  - composer install


- fortifyインストール
  - composer require laravel/fortify
    - （実行後のメッセージ：fortifyのバージョン情報として）
      Using version ^1.19 for laravel/fortify
  - php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"

- モデル・マイグレーションファイル作成
  - php artisan make:model Item -m
    - Item.php
    - 2025_05_25_140719_create_items_table.php
  - php artisan make:model Mylist -m
    - Mylist.php
    - 2025_05_25_140854_create_mylists_table.php
  - php artisan make:model Purchase -m
    - Purchase.php
    - 2025_05_25_141031_create_purchases_table.php

  - php artisan make:model Category -m
    - Category.php
    - 2025_05_25_140000_create_categories_table.php
      - (マイグレーションファイルの時刻部分をリネーム済）
     
- キーを生成
  - php artisan key:generate

- シンボリックリンク作成
  - php artisan storage:link
    - (実行後のメッセージ）
      The [/var/www/public/storage] link has been connected to [/var/www/storage/app/public].
      The links have been created.

- マイグレーション・シーダーの実行
  - php artisan migrate
  - php artisan db:seed
 
- テストコード作成用にduskをインストール
  - composer require laravel/dusk --dev 
