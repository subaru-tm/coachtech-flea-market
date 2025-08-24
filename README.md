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
    - シーダー作成のユーザーログイン情報（メアド / パスワード)
      - test1@test.com / test1pass    // item_id 1~5の出品者
      - test2@test.com / test2pass    // item_id 6~10の出品者
      - test3@test.com / test3pass    // 出品の登録は無し
  - composer require laravel/fortify  // fortifyインストール
    - php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
  - composer require laravel/ui  // メール認証のためlaravel/uiをインストール
    - php artisan ui bootstrap --auth
**  - composer require livewire/livewire // 取引完了モーダル表示のためにインストール
    - php artisan make:livewire CompleteModal
  - メール送信(取引完了の通知)のために実行したコマンド
    - php artisan make:mail MailableMailtrap
    - php artisan serve**
  - cloneして疎通テストを行ったことに伴う留意点（実行はphpコンテナでのHOMEディレクトリです）
    - シーダーまで実行した後、画面が開けない可能性があるので、権限付与をお願いします。
      - chmod -R 777 ./*
        - 【補足】windows環境のためか、cloneすると毎回権限エラーとなるため念のため記載でした。

## 開発環境
- 商品一覧画面 : http://localhost/
- 会員登録画面 : http://localhost/register
- phpMyAdmin  : http://localhost:8080
- マイページ取引中タブ : http://localhost/mypagedealing

## 使用技術(実行環境)
- PHP 7.4.9
- Laravel Framework 8.83.8
- MySQL 8.0.26
- nginx 1.21.1
- laravel/fortify 1.19
- laravel/ui 3.4
- jquery 3.6.0  // ユーザー画像など、選択後にすぐにプレビュー表示する際に使用。**編集ボタン押下により編集可とすることでも使用。**
- beyondcode/laravel-websockets 1.14
- pusher/pusher-php-server 7.2
- laravel-echo 2.2.0
- livewire/livewire 2.12

## ER図
- 今回の対応により追加したテーブルを反映したER図です。
  - 赤枠箇所の２テーブルを追加しています。
  - テーブルに関して、他の箇所での修正は発生しておりません。
<img width="1043" height="634" alt="ER図_Chat追加後Coachtechフリマ 2025-08-24" src="https://github.com/user-attachments/assets/63642ad3-1f32-465e-b011-35acb144c049" />

## 今回対応での留意点(2025年8月24日)
- チャットのメッセージ受信にはリロードが必要です。ご注意ください。
  - リアルタイムチャットとして、pusher, WebSocket, livewireでのイベントリスナーでのリアルタイム受信に挑戦しましたが、時間の都合で実装に至れませんでした。
  - もし、２ユーザーで共にログインした状態でメッセージを連続でやり取りする等の確認をされる場合、大変恐縮ですが、送信後に、受信側でリロードをお願いします。
  - なお、送信後に、受信側がログインして画面を開く分には特に留意点はございません。
- テストについて、今回構築した画面に対するテストコードは用意しておりませんが、手動、目検でテスト済です。
  - 既存のテストコードは、模擬案件１で作成したものです。これをリグレッションテストとして実行して全てpassしたことを確認しております。
  - 今回手を入れた箇所の都合で、テストコード側の不具合も生じて微修正しています。念のためお伝えいたします。
    

（以下、模擬案件1で記載した際のままです。必要に応じてご参照ください)
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
  - テストコード作成: php artisan make:test {テストコード名}(下記参照)
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
