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
 
- テストコード作成用にduskをインストール<br>
  （【参考】Qiitaのこちらのページを参照して実行：https://qiita.com/koi-ken/items/6b435f778a2208155f95）
  - composer require laravel/dusk --dev
  - ChromeDriverをインストールするため、cacert.pem(↓↓のurl)をダウンロードして、cドライブ直下にpemというディレクトリを作成して格納。
    - http://curl.haxx.se/ca/cacert.pem
  - php.iniに下記を追記。
    - curl.cainfo=C:\pem\cacert.pem
  - php artisan dusk:install
  - Chromeを最新版にアップデートして下記のコマンドを実行
    - php artisan dusk:chrome-driver 137.0.7151.69
    - 実行後"ErrorException"が発生（次がそのメッセージ）。一旦無視して継続。
      - file_get_contents(https://chromedriver.storage.googleapis.com/137.0.7151.69/chromedriver_linux64.zip): failed to open stream: HTTP request failed! HTTP/1.0 404 Not Found
  - 各機能ごとにduskファイルを作成（テストケース一覧のD列（各機能）単位で作成）
    - php artisan dusk:make RegisterTest
   
- テストコード
  - テストコード作成: php artisan make:test [テストコード名](下記参照)
  - テストコード実行: php artisan test
  - 各機能に対するテストコード名（~/src/tests/Unit/配下に格納。IDは「テストケース一覧」を引用）
    - ID: 1 会員登録機能　=> RegisterTest
    - ID: 2 ログイン機能  => LoginTest
    - ID: 3 ログアウト機能=> LogoutTest
    - ID: 4 商品一覧取得  => IndexTest
    - ID: 5 マイリスト一覧取得 => MylistTest
    - ID: 6 商品検索機能  => ItemSearchTest
    - ID: 7 商品詳細情報取得 => ItemDetailGetTest
    - ID: 8 いいね機能　  => NiceFunctionTest
