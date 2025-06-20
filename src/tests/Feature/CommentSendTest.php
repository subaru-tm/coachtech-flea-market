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
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Validator;


class CommentSendTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_normalCommentCheck()
    {
        // 1. ユーザーにログインする（seederで作成済のユーザーにて）

        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        $item_id = '9';  // Seederで生成したデータ。
                         // Seederでもログインユーザー(user_id='1'の想定)で
                         // コメント登録していない商品。


          // 商品詳細ページを開く（初期状態でのいいね数を取得）

        $response = $this->get(route('item.detail', ['item_id' => $item_id ]));
        $response->assertStatus(200);

        $responseData = $response->original->getData();
        $beforeCommentCount = $responseData['comment_count'];


        // 2. コメントを入力する

        $testComment = 'テストコメント';  //入力したコメント内容として

        // 3. コメントボタンを押す

        $response = $this->post(route('comment', ['item_id' => $item_id, 'comment' => $testComment ]));
        $response->assertStatus(302);

        $response->assertRedirect(route('item.detail', ['item_id' => $item_id]));
        $response = $this->get(route('item.detail', ['item_id' => $item_id]));

        $responseData = $response->original->getData();
        $afterCommentCount = $responseData['comment_count'];

          // コメントした商品としてDBへ保存されていることを検証
        $this->assertDatabaseHas('mylists', [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'comment' => $testComment,
        ]);

          // viewで表示されているコメント数が１つ増えていることを検証
        $this->assertEquals($beforeCommentCount + 1, $afterCommentCount);

    }

    public function test_unauthorizedUserCommentRejectCheck()
    {
        // ログインしていない（未認証）状態であることを確認
        $this->assertFalse(Auth::check());

        $item_id = '7'; // Seederで生成したデータ。
                        // Seederでもログインユーザー(user_id='1'の想定)で
                        // コメント登録していない商品。

        // 商品詳細ページを開く（初期状態でのいいね数を取得）

        $response = $this->get(route('item.detail', ['item_id' => $item_id ]));
        $response->assertStatus(200);

        // 1. コメントを入力する

        $testComment = '未認証でのコメント';  //入力したコメント内容として

        // 2. コメントボタンを押す

        $response = $this->post(route('comment', ['item_id' => $item_id, 'comment' => $testComment ]));
        $response->assertStatus(403);
            // 未認証でコメント送信すると「403 | This action is unauthorized.」が表示される

    }

    /**
    * @test
    * @dataProvider dataproviderValidation
    */
    public function commentValidationCheck(array $keys, array $values, array $messages, bool $expect)
    {
       $dataList = array_combine($keys, $values);

       $request = new CommentRequest;
       $rules = $request->rules();
       $validator = Validator::make($dataList, $rules);
       $validator = $validator->setCustomMessages($request->messages());
       $result = $validator->passes();
       $this->assertEquals($expect, $result);
       $this->assertSame($messages, $validator->errors()->messages());
    }

    public function dataproviderValidation()
    {
        return [
            'コメントが入力されていない場合、「コメントを入力してください」を表示' => [
                ['comment'],
                [null],
                ['comment' => ['コメントを入力してください']],
                false
            ],
            'コメントが255字以上の場合、「コメントは255文字以内で入力してください」を表示' => [
                ['comment'],
                ['testcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcommenttestcomment'],
                ['comment' => ['コメントは255文字以内で入力してください']],
                false
            ],
        ];
    }

}