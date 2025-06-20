<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Mylist;


class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_keywordSearchCheck()
    {
        $keyword ="ー";   // 全角ハイフンを検索キーワードに指定

        $response = $this->get(route('search', [ 'keyword' => $keyword ]));
        $response->assertStatus(200);

        $responseData = $response->original->getData();
        $responseDataItemsName = collect($responseData['items']->pluck('name'));

        $databaseData = Item::where( 'name', 'like', '%'.$keyword.'%' )->get();
        $databaseDataItemsName = collect($databaseData)->pluck('name');

        //画面へのレスポンスとデータベース検索結果の商品名が一致することを検証
        $this->assertEquals($responseDataItemsName, $databaseDataItemsName);

        ob_get_clean();

    }

    public function test_keepSearchedMylistDisplayCheck()
    {
        // ホームページの検索結果が異なるため、最初にログインする(自身の出品非表示となる)
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();

        $keyword ="ー";   // 全角ハイフンを検索キーワードに指定

        // 1. ホームページで商品を検索
        $response = $this->get(route('search', [ 'keyword' => $keyword ]));
        $response->assertStatus(200);

        // 2. 検索結果が表示される　（表示されたデータを取得）
        $responseKeywordData = $response->original->getData();
        $responseKeywordDataItemsName = collect($responseKeywordData['items']->pluck('name'));

        // 3-1. マイリストページに遷移
        $response = $this->get(route('mylist.keyword', [ 'keyword' => $keyword ]));
        $response->assertStatus(200);

        // 3-2. マイリストページで表示されたデータを取得
        $responseMylistData = $response->original->getData();
        $responseMylistDataItemsId = collect($responseMylistData['items']->pluck('id'));

        // 比較検証用として、DBから直接データを取得。（Mylistのいいね商品 && Keyword検索結果）
        $user_id = Auth::id();
        $databaseMylistNiceItem = Mylist::where('user_id', $user_id)->where('nice_flug', '1')->get();
        $databaseMylistNiceItemId = collect($databaseMylistNiceItem)->pluck('item_id');


        $databeseKeywordData = Item::where( 'name', 'like', '%'.$keyword.'%' )->get();
        $databaseKeywordDataItemsId = collect($databeseKeywordData)->pluck('id');


        $databaseResultDataId = $databaseKeywordDataItemsId->intersect($databaseMylistNiceItemId);

        // マイページのレスポンス(3-2.のデータ)とDB取得結果のitem_idの一致を検証
        $this->assertEquals($responseMylistDataItemsId, $databaseResultDataId);

        ob_get_clean();
        ob_get_clean();
    }
}
