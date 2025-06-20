<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;

class ProfileGetTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_mypageDisplayCheck()
    {
        // 1. ユーザーにログインする（seederで作成済のユーザーにてログインする）
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        // 2. プロフィールページを開く
        // 2-1. 出品タグ( 'tag' => 'sell' )での表示
        $response = $this->get(route('mypage', ['tab' => 'sell']));
        $response->assertStatus(200);

        $responseViewContent = $response->getContent();

          // プロフィール画像の表示を検証。
          // viewのcontent-heading要素の配下が該当画像(storage内に保存)の表示箇所。
          // タグ名等でマッチングさせ、存在していれば表示されているとして検証する
        $pattern = '/<div class="content-heading">\n.*?<img src="storage.*?>\n/';
        preg_match_all($pattern, $responseViewContent, $matches);
        $targetResponse = $matches[0] ?? null;

        $this->assertNotEmpty($targetResponse);

          // ユーザー名の検証。上記同様、viewのcontent-heading要素の配下の
          // content-heading__nameの存在を確認して検証する
        $pattern = '/<div class="content-heading">\n.*?<img.*?\n.*?<div class="content-heading__name">.*?div>\n/';
        preg_match_all($pattern, $responseViewContent, $matches);
        $targetResponse = $matches[0] ?? null;
  
        $this->assertNotEmpty($targetResponse);

          // 出品した商品一覧の表示を検証。
          // responseの商品データと、database同条件(ユーザーが出品した商品)の抽出データを比較し一致を検証。
        $responseData = $response->original->getData();
        $responseItemsData = $responseData['items']->toArray();

        $databaseItemsData = Item::where('user_id', $user_id)->get()->toArray();

        $this->assertEquals($responseItemsData, $databaseItemsData);


        // 2-2. 購入タグ( 'tag' => 'buy' )での表示
          // 念のため、プロフィール画像、ユーザー名もチェック（2-1同様）
        $response = $this->get(route('mypage', ['tab' => 'buy']));
        $response->assertStatus(200);

        $responseViewContent = $response->getContent();

          // プロフィール画像の表示を検証。
        $pattern = '/<div class="content-heading">\n.*?<img src="storage.*?>\n/';
        preg_match_all($pattern, $responseViewContent, $matches);
        $targetResponse = $matches[0] ?? null;

        $this->assertNotEmpty($targetResponse);

          // ユーザー名の検証。
        $pattern = '/<div class="content-heading">\n.*?<img.*?\n.*?<div class="content-heading__name">.*?div>\n/';
        preg_match_all($pattern, $responseViewContent, $matches);
        $targetResponse = $matches[0] ?? null;
  
        $this->assertNotEmpty($targetResponse);

          // 購入した商品一覧の表示を検証。
          // responseの購入データと、database(ユーザーの購入データ)の抽出データを比較し一致を検証。
        $responseData = $response->original->getData();
        $responsePurchasesData = $responseData['purchases']->toArray();

        $databasePurchasesData = Purchase::where('user_id', $user_id)->get()->toArray();

        $this->assertEquals($responsePurchasesData, $databasePurchasesData);

    }
}
