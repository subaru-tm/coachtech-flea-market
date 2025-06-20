<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Item;


class PaymentMethodSellectTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        // seederで作成済のユーザーにてログインする
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        
        $item_id = '5';  // Seederで生成したデータ。
                         // まだ購入されていない商品。


        // 1. 支払い方法選択画面を開く（＝商品購入画面を開く）

        $response = $this->get(route('purchase', ['item_id' => $item_id ]));
        $response->assertStatus(200);

        $responseData = $response->original->getData();
        $responseShipping = $responseData['shipping'];

        // 2. プルダウンメニューから支払い方法を選択する
        $payment_method = "カード払い";  // 画面で選択する入力値として

        $response = $this->get(route('purchase', [
            'item_id' => $item_id,
            'payment_method' => $payment_method,
        ]));
          // タグを除くテキストのみを検証するassertSeeTextを使い、
          // 入力が表示されていることを検証。
        $response->assertSeeText($payment_method);

    }
}
