<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Mylist;


class NiceFunctionTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_NiceEntryCheck()
    {
        // 1. ユーザーにログインする（seederで作成済のユーザーにて）

        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        $item_id = '9';  // Seederで生成したデータ。
                         // Seederでもログインユーザー(user_id='1'の想定)で
                         // いいね登録していない商品。


        // 2. 商品詳細ページを開く（初期状態でのいいね数を取得）

        $response = $this->get(route('item.detail', ['item_id' => $item_id ]));
        $response->assertStatus(200);

        $responseData = $response->original->getData();
        $beforeNiceCount = $responseData['nice_count'];


        // 3. いいねアイコンを押下

        $response = $this->post(route('nice', ['item_id' => $item_id ]));
        $response->assertStatus(302);

        $response->assertRedirect(route('item.detail', ['item_id' => $item_id]));
        $response = $this->get(route('item.detail', ['item_id' => $item_id]));

        $responseData = $response->original->getData();
        $afterNiceCount = $responseData['nice_count'];

          // いいねした商品としてDBへ登録されていることを検証
        $this->assertDatabaseHas('mylists', [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'nice_flug' => '1',    // '1'がいいねしている状態を示す
        ]);

          // viewで表示されている「いいね数」が１つ増えていることを検証
        $this->assertEquals($beforeNiceCount + 1, $afterNiceCount);

    }

    public function test_entriedNiceIconCheck()
    {
        // 1. ユーザーにログインする

        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();

        $item_id = '10';  
          // Seederで生成したデータ。上記Check同様、いいね未登録。


        // 2. 商品詳細ページを開く（初期状態でのいいねアイコンイメージを取得）

        $response = $this->get(route('item.detail', ['item_id' => $item_id ]));
        $response->assertStatus(200);

        $viewContent = $response->getContent();
        $pattern = '/<img.*?src="(.*?)".*?>/i';
        preg_match_all($pattern, $viewContent, $matches);

          // ３つ目のimgタグのsrcが、いいねボタン用の画像のためsrc名を取得。
        $srcResponse = $matches[1][2] ?? null;

          // 色が付いていないいいねアイコンイメージファイルURL（☆マークの画像）
        $srcBeforeNiceImage = "http://localhost/storage/nice-button.png";

         // viewに初期表示は色が付いていない☆ボタンのファイルであることを検証
        $this->assertEquals($srcBeforeNiceImage, $srcResponse);


        // 3. いいねアイコンを押下（押下前と同様、view表示された「いいね」のイメージ取得。

        $response = $this->post(route('nice', ['item_id' => $item_id ]));
        $response->assertStatus(302);
   
        $response->assertRedirect(route('item.detail', ['item_id' => $item_id]));
        $response = $this->get(route('item.detail', ['item_id' => $item_id]));
   
        $viewContent = $response->getContent();
        $pattern = '/<img.*?src="(.*?)".*?>/i';
        preg_match_all($pattern, $viewContent, $matches);

        $srcResponse = $matches[1][2] ?? null;

          // 色が付いているいいねアイコンイメージファイル（★マークの画像）
        $srcAfterNiceImage = "http://localhost/storage/nice-button-pushed.png";

        // アイコン押下後、色付きの★アイコンがviewに表示されていることを検証
        $this->assertEquals($srcAfterNiceImage, $srcResponse);

    }

    public function test_CancellationNiceCheck()
    {
        // 1. ユーザーにログインする（seederで作成済のユーザーにて）

        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        $item_id = '8';  // Seederで生成したデータ。
                         // Seederでのログインユーザー(user_id='1'の想定)で
                         // いいね登録済の商品。

          // いいねされているDB状態を念のため確認
        $this->assertDatabaseHas('mylists', [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'nice_flug' => '1',    // '1'はいいね有効。
            ]);


        // 2. 商品詳細ページを開く

        $response = $this->get(route('item.detail', ['item_id' => $item_id ]));
        $response->assertStatus(200);

        $responseData = $response->original->getData();
        $beforeNiceCount = $responseData['nice_count'];

          // 念のため商品詳細ページでいいねアイコンが登録中（色付き★マーク）を確認。
        $viewContent = $response->getContent();
        $pattern = '/<img.*?src="(.*?)".*?>/i';
        preg_match_all($pattern, $viewContent, $matches);

          // imgタグの３つ目のsrcが、いいねボタン用の画像。src内容を取得。
        $srcResponse = $matches[1][2] ?? null;

          // 色が付いていないいいねアイコンイメージファイルURL（☆マークの画像）
        $srcBeforeNiceImage = "http://localhost/storage/nice-button-pushed.png";

         // viewに初期表示は色が付いていない☆ボタンのファイルであることを検証
        $this->assertEquals($srcBeforeNiceImage, $srcResponse);


        // 3. いいねアイコンを押下

        $response = $this->post(route('nice', ['item_id' => $item_id ]));
        $response->assertStatus(302);

        $response->assertRedirect(route('item.detail', ['item_id' => $item_id]));
        $response = $this->get(route('item.detail', ['item_id' => $item_id]));

          // いいね解除を、アイコンイメージ（色なしの☆マーク）、DB状態で確認
        $viewContent = $response->getContent();
        $pattern = '/<img.*?src="(.*?)".*?>/i';
        preg_match_all($pattern, $viewContent, $matches);

        $srcResponse = $matches[1][2] ?? null;

            // 色がないいいねアイコンイメージファイル（☆マークの画像）
        $srcCancellationNiceImage = "http://localhost/storage/nice-button.png";

            // いいね解除後は、色なしの☆アイコンが表示されていることを検証
        $this->assertEquals($srcCancellationNiceImage, $srcResponse);
            // いいね解除がDBへ反映されていることを検証（削除されずにnice_flugを更新）
        $this->assertDatabaseHas('mylists', [
        'user_id' => $user_id,
        'item_id' => $item_id,
        'nice_flug' => '0',    // '0'がいいね解除状態を示す
        ]);
        

          // いいね数が減少していることを確認
        $responseData = $response->original->getData();
        $afterNiceCount = $responseData['nice_count'];

          // viewで表示されている「いいね数」が１つ減っていることを検証
        $this->assertEquals($beforeNiceCount - 1, $afterNiceCount);
    }
}
