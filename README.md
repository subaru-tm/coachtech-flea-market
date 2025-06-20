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

- マイグレーション・シーダーの実行について
  - テストコードでは各ファイルでの実行時にseederでデータをリフレッシュしています。
  - このため、テストコード実行においてはseederの実行は不要となりますが、画面を参照する場合向けとして、下記コマンドを実行ください。
    - php artisan migrate
    - php artisan db:seed
 
- メール認証のためlaravel/uiをインストール
  - composer require laravel/ui
    - ⇒実行後のメッセージよりバージョン情報：Using version ^3.4 for laravel/ui
  - php artisan ui bootstrap --auth
  - 【補足】mailtrapでアカウントを作成し、Sandbox環境での認証メール送信・受信としております。
      - メール認証をご確認される場合は、下記にてログインの上で認証処理を行ってください。
        - ログイン(メアド):pleiades_tm@yahoo.co.jp
          - (参考)アカウントID：2330889
        - パスワード　：Test1@laravel
      - 今回の模擬案件のために作成したアカウントのため、個人情報等のご心配は無用です。（念のため）
      - なお、要件ご提示いただいた画面イメージのように、アカウント作成後にメール認証をガイド(再送信のリンク付き)するviewは用意したものの、その中に画面イメージ通りの認証ボタン「認証はこちらから」は用意できませんでした。（挑戦しましたが難解であり、頓挫しました）
      - つまり、メール認証を行うには上記のmailtrapアカウントにログインするしかない、と思いますので念のためお伝えいたします。

   
- テストコード
  - テストコード作成: php artisan make:test [テストコード名](下記参照)
  - テストコード実行: php artisan test
  - 各機能に対するテストコード名（~/src/tests/Unit/配下に格納。IDは「テストケース一覧」を引用）
    - ID: 1 会員登録機能　　　 => RegisterTest
    - ID: 2 ログイン機能　　　 => LoginTest
    - ID: 3 ログアウト機能　　 => LogoutTest
    - ID: 4 商品一覧取得　　　 => IndexTest
    - ID: 5 マイリスト一覧取得 => MylistTest
    - ID: 6 商品検索機能　　　 => ItemSearchTest
    - ID: 7 商品詳細情報取得　 => ItemDetailGetTest
    - ID: 8 いいね機能　　　　 => NiceFunctionTest
    - ID: 9 コメント送信機能　 => CommentSendTest
    - ID:10 商品購入機能　　　 => PurchaseTest
    - ID:11 支払い方法選択機能 => PaymentMethodSellectTest
    - ID:12 配送先変更機能　　 => AddressEditTest
    - ID:13 ユーザー情報取得　 => ProfileGetTest
    - ID:14 ユーザー情報変更　 => ProfileEditTest
    - ID:15 出品商品情報登録　 => ExhibitionTest
