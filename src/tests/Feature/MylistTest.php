<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Mylist;
use App\Models\Purchase;


class MylistTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_niceItemsCheck()
    {
        // seederで作成済のユーザーにてログインする
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();

        $response = $this->get('/')->assertViewIs('index');
        $response->assertStatus(200);
        
        // マイリストページを開き、ログインユーザーがいいねをした商品の表示を確認
        $response = $this->get('/mylist')->assertViewIs('index');
        $response->assertStatus(200);

        // viewに表示されたアイテム一覧のitem_idを取得
        $responseData = $response->original->getData();
        $responseItems = collect($responseData['items']);
        $responseItemsId = $responseItems->pluck('id');

        // mylistsテーブルの中でログイン中のユーザーが「いいね」をしたitem_idを取得
        $user_id = Auth::id();
        $databaseMyNiceItems = Mylist::where('user_id', $user_id)->where('nice_flug', '1')->get();
        $databaseMyNiceItemsId = $databaseMyNiceItems->pluck('item_id');

        // ログイン中ユーザー自身が出品したitemを除く。
        $myExDatabeseItems = Item::where('user_id', $user_id)->get();
        $myExDatabeseItemsId = $myExDatabeseItems->pluck('id');
        $databaseMyNiceItemsIds = $databaseMyNiceItemsId->diff($myExDatabeseItemsId->toArray())->toArray();

        // view表示のitem_idと、ログイン中のユーザーの「いいね」のitem_idを比較
        // ここでは一致していることを検証。データベースの値次第で、配列内の順番は変わるため順不同検証
        $this->assertEquals($responseItemsId->toArray(), array_values($databaseMyNiceItemsIds));

        // 1行では警告が消えなかったため、出力バッファを削除する処理を2行追加
        ob_get_clean();
        ob_get_clean();

    }

    public function test_soldItemCheck()
    {
        $stringToCheck = 'Sold';

        // seederで作成済のユーザーにてログインする
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        
        $response = $this->get('/mylist')->assertViewIs('index');

        //viewの表示にて'Sold'の表示があるか検証し、ステータスコードも検証
        $response->assertSee($stringToCheck);
        $response->assertStatus(200);

        $responseData = $response->original->getData();
        $databaseData = Purchase::all();


        //念のため、purchaseテーブルも全件取得できているか検証
        $this->assertEquals($responseData['purchases'], $databaseData);

        ob_get_clean();

    }

    public function test_myExhibitionItemNotDisplayCheck()
    {
        // seederで作成済のユーザーにてログインする
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();

        $response = $this->get('/mylist')->assertViewIs('index');
        $response->assertStatus(200);

        // viewに表示されたアイテム一覧のitem_idを取得
        $responseData = $response->original->getData();
        $responseItems = collect($responseData['items']);
        $responseItemsId = $responseItems->pluck('id');


        // itemsテーブルの中でログイン中のユーザーが出品したitem_idを取得
        $user_id = Auth::id();
        $myExDatabeseItems = Item::where('user_id', $user_id)->get();
        $myExDatabeseItemsId = $myExDatabeseItems->pluck('id');

        // view表示のitem_idと、ログイン中のユーザーの出品item_idを比較
        // 今回は全て差分として出る（一致しているものがないことの検証）
        $diff = $responseItemsId->diff($myExDatabeseItemsId->toArray());


        // 最終的にview表示のitem_id配列と上記の差分が一致していることで検証
        $this->assertEquals($responseItemsId->toArray(), $diff->toArray());

        ob_get_clean();

    }

    public function test_noAuthNoDisplayCheck()
    {
        // 未認証の場合、マイリストを開いても何も表示されないことを検証

        if(Auth::check()) {
            // まずはログイン状態であればログアウトを実行
            $response = $this->get(route('logout'));
        }

        // ログアウト状態を確認
        $this->assertFalse(Auth::check());

        $response = $this->get('/mylist')->assertViewIs('index');
        $response->assertStatus(200);

        // マイリストボタン押下後のレスポンスデータが空であることを検証
        $responseData = $response->original->getData();
        $this->assertEmpty($responseData);

        ob_get_clean();

    }
}
