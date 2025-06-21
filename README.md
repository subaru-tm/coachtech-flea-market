# Coachtech-flea-market

## 環境構築
- Dockerビルド
  - git clone git@github.com:coachtech-material/laravel-docker-template.git
  - mv laravel-docker-template/ coachtech-flea-market
  - docker-compose up -d --build

- Laravel環境構築
  - docker-compose exec php bash
  - composer install
  - cp .env.example .env  // 環境変数を設定
  - php artisan key:generate
  - php artisan storage:link  // シンボリックリンク作成
  - php artisan migrate
  - php artisan db:seed
  - composer require laravel/fortify  // fortifyインストール
    - php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
  - composer require laravel/ui  // メール認証のためlaravel/uiをインストール
    - php artisan ui bootstrap --auth

## 開発環境
- 商品一覧画面 : http://localhost/
- 会員登録画面 : http://localhost/register
- phpMyAdmin  : http://localhost:8080

## 使用技術(実行環境)
- PHP 7.4.9
- Laravel Framework 8.83.8
- MySQL 8.0.26
- nginx 1.21.1
- laravel/fortify 1.19
- laravel/ui 3.4

## ER図

![image](https://github.com/user-attachments/assets/dd5d1fd9-1ae0-4313-895d-d503fdf5bf72)

## その他（応用要件に伴うアカウント情報やテストコードについて）
- 応用要件について
  - 一通り実装をしております。それぞれの要件実装について解説・補足致します。
    - **メールを用いた認証機能**
      - mailtrapを使用して実装しています。.envに設定反映してあります。
      - mailtrapアカウント情報
        - Sandbox環境での認証メール送信・受信を実装しています。
        - メール認証処理をご確認される場合は、下記にてmailtrapにログインの上、認証処理を行ってください。
          - ログイン(メアド):pleiades_tm@yahoo.co.jp
            - (参考)アカウントID：2330889
          - パスワード　：Test1@laravel
        - 今回の模擬案件のために作成したアカウントのため、個人情報等のご心配は無用です。（念のため）
        - なお、上記アカウントにログインしないとメール認証できない認識です。
          - Figma画面イメージのメール認証view(再送信のリンク付き)は用意したものの、その中の認証ボタン「認証はこちらから」は実装できませんでしたのでお伝えいたします。（挑戦しましたが難解であり、頓挫しました）
    - **検索状態がマイリストでも保持**
      - routeにて'mylist.keyword'とのnameで登録されたもので処理しています
      - 機能要件では、キーワード検索 →→ マイリスト表示での指定かと思われますが、マイリスト表示の後にキーワード検索を行ってもAND結果が表示できるようにしております
    - **「購入する」ボタン押下後、stripe決済画面に接続**
      - stripeがgitで公開しているサンプルプロジェクトから決済画面と思われるhtml,cssをコピーしました。
        - コピー元：https://github.com/stripe-samples/accept-a-payment/tree/main/payment-element
        - コピー先
          - /src/resources/views/stripe/stripe-index.blade.php
          - /src/public/css/stripe/base.css
        - なお、当stripe画面を表示した先の遷移画面はログインが必要のようですが、今回stripeのアカウントは作成しておりません。
        - また、上記のようにコピーして使用するよりは、APIなどで呼び出した方がよいかと思いましたが、そこまでの実装に至れませんでした。
   - **該当画像はlaravelのstorageディレクトリに保存されていること**
     - ユーザー画像、出品商品画像のそれぞれで実装しています。
     - 上述の「シンボリックリンク作成」がこの要件実装のためです。
- 応用要件は以上との認識です。
 
   
- テストコードファイル名と機能の紐付き
  - テストコード作成: php artisan make:test [テストコード名](下記参照)
  - テストコード実行: php artisan test
  - 各機能に対するテストコード名（~/src/tests/Feature/配下に格納。IDは「テストケース一覧」を引用）
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
