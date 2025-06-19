<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Item;


class AddressEditTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_addressEditDisplayCheck()
    {
        // 1. ユーザーにログインする（seederで作成済のユーザーにて）
 
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();
 
        $item_id = '5';  // Seederで生成したデータ。
                         // まだ購入されていない商品。
 
 
        // 2. 送付先住所変更画面で住所を登録する

        $testDataPostCode = '123-4567';
        $testDataAddress = '変更テスト住所';
        $testDataBuilding = '変更テスト建物名';
          // 検証用にテスト入力データを配列にしておく
        $testShipping = [
            'shipping_post_code' => $testDataPostCode,
            'shipping_address' => $testDataAddress,
            'shipping_building' => $testDataBuilding,
        ];

        $response = $this->post(route('address.update', [
            'item_id' => $item_id,
            'shipping_post_code' => $testDataPostCode,
            'shipping_address' => $testDataAddress,
            'shipping_building' => $testDataBuilding,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('purchase', ['item_id' => $item_id ]));
 
        // 3. 商品購入画面を再度開く
 
        $response = $this->get(route('purchase', ['item_id' => $item_id ]));
        $responseData = $response->original->getData();

        // テスト入力データの住所3項目の配列と、再度開いた画面の住所3項目の一致を検証
        $this->assertEquals($testShipping, $responseData['shipping']);
            // payment_methodが登録されていれば購入完了のステータスになる
            // 住所はデフォルト値で更新なしのため、検証対象から除外。
 
    }

    public function test_addressEditDatabaseCheck()
    {
        // 1. ユーザーにログインする（seederで作成済のユーザーにて）
 
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();
 
        $item_id = '6';  // Seederで生成したデータ。
                         // まだ購入されていない商品。
 
 
        // 2. 送付先住所変更画面で住所を登録する

        $testDataPostCode = '987-6543';
        $testDataAddress = '変更DB反映テスト住所';
        $testDataBuilding = '変更DB反映テスト建物名';

        $response = $this->post(route('address.update', [
            'item_id' => $item_id,
            'shipping_post_code' => $testDataPostCode,
            'shipping_address' => $testDataAddress,
            'shipping_building' => $testDataBuilding,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('purchase', ['item_id' => $item_id ]));
 
        // 3. 商品を購入する

        $testPaymentMethod = "カード払い"; // 選択必須のためテスト入力値とする
        $testPaymentMethodClassification = "2"; // 入力ではないがDB登録時に変換される想定

        $response = $this->post(route('purchase.commit', [
            'item_id' => $item_id,
            'payment_method' => $testPaymentMethod,
            'shipping_post_code' => $testDataPostCode,
            'shipping_address' => $testDataAddress,
            'shipping_building' => $testDataBuilding,
        ]));

        // 結果がデータベースに反映されていることを検証
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'payment_method' => $testPaymentMethodClassification, //tinyint型に変換される
            'shipping_post_code' => $testDataPostCode,
            'shipping_address' => $testDataAddress,
            'shipping_building' => $testDataBuilding,            
        ]);
            // payment_methodが登録されていれば購入完了のステータスになる
            // 住所はデフォルト値で更新なしのため、検証対象から除外。
 
    }

}
