<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\Item;
use App\Models\Mylist;


class ItemDetailGetTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;

    /**
     * A basic feature test example.
     * @dataProvider dataproviderItemKey
     *
     * @return void
     */
    public function test_allFieldGetCheck(string $key, bool $expect)
    {
        // 商品詳細画面で必要な情報が表示されることを検証

        $item_id = '6';  // Seederで生成したデータ。カテゴリー数は１つ。

        // コントローラからのレスポンスで商品詳細画面に表示されるデータを取得
        $response = $this->get(route('item.detail', ['item_id' => $item_id ]));
        $response->assertStatus(200);

        $responseData = $response->original->getData();
        $responseItemData = collect($responseData['item'])->toArray();
        $responseOtherComment = $responseData['other_comment'];

        // DBに直接商品IDを指定して取得
        $databaseItemData = Item::with('categories')->find($item_id);
        $databaseNiceCount = Mylist::where('item_id', $item_id)->where('nice_flug', '1')->count();
        $databaseCommentCount = Mylist::where('item_id', $item_id)->where('comment', '<>', '')->count();
        $databaseOtherComment = Mylist::where('item_id', $item_id)->where('comment', '<>', '')->first();
          // カテゴリーは中間テーブル経由でデータ形式が異なるため配列に変換
        $databaseItemCategories = $databaseItemData['categories']->toArray();

        // 各項目ごとに検証
          // itemsテーブルの項目は共通でdataproviderItemKeyを読む（カテゴリーを除く）
        $this->assertEquals($responseItemData[$key], $databaseItemData[$key]);

          // いいね数が取得できているか検証
        $this->assertEquals($responseData['nice_count'], $databaseNiceCount);

          // コメント数が取得できているか検証
        $this->assertEquals($responseData['comment_count'], $databaseCommentCount);

          // カテゴリーが取得できているか検証
        $this->assertEquals($responseItemData['categories'], $databaseItemCategories);

          // 他のコメントが取得できているか検証
        $this->assertEquals($responseOtherComment['user_id'], $databaseOtherComment['user_id']);
        $this->assertEquals($responseOtherComment['comment'], $databaseOtherComment['comment']);

    }

    public function dataproviderItemKey()
    {
        return [
            '商品画像が取得できているか' => [ 'image', true ],
            '商品名が取得できているか' => [ 'name', true ],
            'ブランド名が取得できているか' => [ 'brand', true ],
            '価格が取得できているか' => [ 'price', true ],
            '商品説明が取得できているか' => [ 'description', true ],
            '商品の状態が取得できているか' => [ 'condition', true ],
        ];
    }

    public function test_multipleCategoriesItemCheck()
    {
        // 商品詳細画面で複数カテゴリーが選択済の商品でも適切に表示されていることを検証

        $item_id = '1';  // Seederで生成したデータ。カテゴリー数は１つ。

        $response = $this->get(route('item.detail', ['item_id' => $item_id ]));
        $response->assertStatus(200);
        $responseData = $response->original->getData();
        $responseItemData = collect($responseData['item'])->toArray();

        $databaseItemData = Item::with('categories')->find($item_id);
        $databaseItemCategories = $databaseItemData['categories']->toArray();

        $this->assertEquals($responseItemData['categories'], $databaseItemCategories);        

    }
}
