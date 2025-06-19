<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Tests\TestCase;

class IndexTest extends TestCase
{

    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_allItemGetCheck()
    {
        $response = $this->get('/')->assertViewIs('index');

        $response->assertStatus(200);

        $responseData = $response->original->getData();
        $databaseData = Item::all();

        // purchasesテーブルも取得しているため、itemsテーブルのみ全権取得OKか検証する

        $this->assertEquals($responseData['items'], $databaseData);


        // 警告が出たため、出力バッファを削除する処理を実施
        ob_get_clean();

    }

    public function test_soldItemCheck()
    {

        $stringToCheck = 'Sold';

        $response = $this->get('/')->assertViewIs('index');

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

        $response = $this->get('/')->assertViewIs('index');
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


}
