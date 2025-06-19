<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemCategory;


class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ExhibitionEntryCheck()
    {
        // 1. ユーザーにログインする
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        // 2. 商品出品画面を開く

        $response = $this->get('/exhibition');
        $response->assertStatus(200);

        // 3. 各項目に適切な情報を入力して保存する

          // 入力値（fileとpriceは入力値とDB保存内容が異なるため、保存後の想定も記述）
        $img_file = UploadedFile::fake()->image('test.jpeg', '150', '150');  // 商品画像ファイルの取得。
        $file_path = 'storage/test.jpeg'; // 検証用。DBのimage項目には、この様に格納されるはず。
        $file_storage_path = '/app/public/test.jpeg'; // storageディレクトリへの画像ファイル保存検証用。
        $category = ['1', '3', '4'];  // カテゴリーの選択値（複数選択）
        $condition = "1"; // 商品の状態。"良好"を選んだ結果としてvalue="1"がリクエストに渡される
        $name = "テスト出品商品";  // 商品名
        $brand = "テストブランド"; // ブランド名。バリデーションルールはないが（空白可だが）ここでは入力。
        $description = "テスト用商品説明。この欄は商品の説明をします。"; // 商品の説明。
        $price = "¥99999"; // 販売価格の入力値（requestの値）。
        $expectPrice = "99999"; // DB検証用の販売価格。DB登録時にコントローラで¥を取り除く実装をしている。

          // 入力値をpostする
        $response = $this->post('/exhibition', [
            'img_file' => $img_file,
            'category' => $category,
            'condition' => $condition,
            'name' => $name,
            'brand' => $brand,
            'description' => $description,
            'price' => $price,
        ]);
          // リダイレクトを検証。
        $response->assertStatus(302);
        $response->assertRedirect('/mypagesell');

        // 正しく保存されていることの検証を３箇所行う（itemsテーブル、item_categoryテーブル、storage配下） 
          // 3-1. database (itemsテーブル) に期待通り保存されていることを検証。
        $this->assertDatabaseHas('items', [
            'user_id' => $user_id,
            'name' => $name,
            'brand' => $brand,
            'condition' => $condition,
            'description' => $description,
            'price' => $expectPrice,
            'image' => $file_path,
        ]);

            // 次の検証用に同じ条件でinsertされたitemsテーブルのレコードを取得し、idを取得する。
        $databaseData = Item::where('user_id', $user_id)->where('name', $name)->where('brand', $brand)->where('condition', $condition)->where('description', $description)->where('price', $expectPrice)->where('image', $file_path)->first();

        $databaseItemArray = collect($databaseData)->toArray();
        $new_item_id = $databaseItemArray['id'];

          // 3-2. database（item_categoryテーブル）にもcategoryが(複数)登録されているか検証。
        $databaseItemCategory = ItemCategory::where('item_id', $new_item_id)->get();
        $databaseCategoriesId = $databaseItemCategory->pluck('category_id')->toArray();

        $this->assertEquals($category, $databaseCategoriesId);

          // 3-3. storageディレクトリ配下に商品画像ファイルが保存されていることを検証。
        $this->assertFileExists(storage_path($file_storage_path));
    }
}
