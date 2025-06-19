<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Item;


class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_normalPurchaseCheck()
    {
        // 1. ユーザーにログインする（seederで作成済のユーザーにて）

        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        $item_id = '5';  // Seederで生成したデータ。
                         // まだ購入されていない商品。


        // 2. 商品購入画面を開く

        $response = $this->get(route('purchase', ['item_id' => $item_id ]));
        $response->assertStatus(200);

        $responseData = $response->original->getData();
        $responseShipping = $responseData['shipping'];

        // 3. 商品を選択して「購入する」ボタンを押下

          // 商品購入画面を開いている時点で商品が選択されている前提となるため、
          // 正常終了のために必要な支払い方法を入力したものとする
        $payment_method = "カード払い";

          // 「購入する」ボタンを押下、viewからのRequestとして下記を送信。
        $response = $this->post(route('purchase.commit', [
            'item_id' => $item_id,
            'payment_method' => $payment_method,
            'shipping_post_code' =>$responseShipping['shipping_post_code'],
            'shipping_address' =>$responseShipping['shipping_address'],
            'shipping_building' =>$responseShipping['shipping_building'],
        ]));
        $response->assertStatus(302);

        $response->assertRedirect(route('index'));

        // 購入が完了したことをデータベースの状態で検証

        $payment_method_classification = "2";
         // payment_methodは、DB登録の際にtinyint型へ変換。"2" が "カード払い" を意味。

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'payment_method' => $payment_method_classification,
        ]);
            // payment_methodが登録されていれば購入完了のステータスになる
            // 住所はデフォルト値で更新なしのため、検証対象から除外。

    }

    public function test_purchasedItemDisplaySoldCheck()
    {
        // 1. ユーザーにログインする（seederで作成済のユーザーにて）

        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        $item_id = '6';  // Seederで生成したデータ。
                         // まだ購入されていない商品。


        // 2. 商品購入画面を開く

        $response = $this->get(route('purchase', ['item_id' => $item_id ]));
        $response->assertStatus(200);

        $responseData = $response->original->getData();
        $responseShipping = $responseData['shipping'];

        // 3. 商品を選択して「購入する」ボタンを押下

          // 商品購入画面を開いている時点で商品が選択されている前提となるため、
          // 正常終了のために必要な支払い方法を入力したものとする
        $payment_method = "コンビニ払い";

          // 「購入する」ボタンを押下、viewからのRequestとして下記を送信。
        $response = $this->post(route('purchase.commit', [
            'item_id' => $item_id,
            'payment_method' => $payment_method,
            'shipping_post_code' =>$responseShipping['shipping_post_code'],
            'shipping_address' =>$responseShipping['shipping_address'],
            'shipping_building' =>$responseShipping['shipping_building'],
        ]));
        $response->assertStatus(302);

        $response->assertRedirect(route('index'));

          // 購入が完了したことをデータベースの状態で検証
        $payment_method_classification = "1";
         // payment_methodは、DB登録の際にtinyint型へ変換。"1" が "コンビニ払い" を意味。

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'payment_method' => $payment_method_classification,
        ]);

        // 4. 商品一覧画面を表示する        
        $response = $this->get(route('index'));
        $responseViewContent = $response->getContent();

          // 対象商品のアイテムカード(のULR)と、購入済のSold文字列を検索する
        $targetItemCard = 'http://localhost/item/:'.$item_id;
        $expectText = 'Sold';

          // まずはそれぞれ、単独でresponseに表示されていることを確認
        $response->assertSee($targetItemCard, false); // タグを含むview全体の検索
        $response->assertSeeText($expectText); // タグを除くテキスト検索

          // 上記のみだと別商品のSoldでも通ってしまうため、今回の商品から実装したview構文を
          // 考慮した上で、Soldが表示されているべき場所に存在しているかを検証

        $pattern = '/item.*?6">\n.*?<div class="item-card__image">\n.*?<img.*?jpg.*?>\n.*?>\n\n.*?\n.*?>Sold<.*?\n/';
        preg_match_all($pattern, $responseViewContent, $matches);
        $targetItemResponse = $matches[0] ?? null;

        $this->assertNotEmpty($targetItemResponse);
          // 特定条件(上記の$pattern: item_idが一致するURLの6行先にSoldが存在する場合)の
          // 合致により、結果の配列が存在していればviewにSoldが表示されたと判定する

        ob_get_clean();
    }

    public function test_purchasedItemProfileDisplayCheck()
    {
        // 1. ユーザーにログインする（seederで作成済のユーザーにて）

        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        $item_id = '8';  // Seederで生成したデータ。
                         // まだ購入されていない商品。


        // 2. 商品購入画面を開く

        $response = $this->get(route('purchase', ['item_id' => $item_id ]));
        $response->assertStatus(200);

        $responseData = $response->original->getData();
        $responseShipping = $responseData['shipping'];

        // 3. 商品を選択して「購入する」ボタンを押下

          // 商品購入画面を開いている時点で商品が選択されている前提となるため、
          // 正常終了のために必要な支払い方法を入力したものとする
        $payment_method = "カード払い";

          // 「購入する」ボタンを押下、viewからのRequestとして下記を送信。
        $response = $this->post(route('purchase.commit', [
            'item_id' => $item_id,
            'payment_method' => $payment_method,
            'shipping_post_code' =>$responseShipping['shipping_post_code'],
            'shipping_address' =>$responseShipping['shipping_address'],
            'shipping_building' =>$responseShipping['shipping_building'],
        ]));
        $response->assertStatus(302);

        $response->assertRedirect(route('index'));

          // 購入が完了したことをデータベースの状態で検証
        $payment_method_classification = "2";
          // payment_methodは、DB登録の際にtinyint型へ変換。"2" が "カード払い" を意味。

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'payment_method' => $payment_method_classification,
        ]);

        // 4. プロフィール画面をを表示する
          // プロフィール画面の購入した商品一覧を表示するため、'tab'は'buy'を指定
        $response = $this->get(route('mypage', ['tab' => 'buy']));

        $targetItemCard = 'http://localhost/item/:'.$item_id;

        $response->assertSee($targetItemCard, false); // タグを含むview全体の検索
          // OKであれば該当商品のアイテムカードが購入した商品一覧に追加表示されたと判定できる

    }
}
