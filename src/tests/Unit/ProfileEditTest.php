<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_profileEditDisplayCheck()
    {
        // 1. ユーザーにログインする（seederで作成済のユーザーにてログインする）
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        // 2. プロフィールページを開く（プロフィール編集画面を開く）
        $response = $this->get(route('profile.edit'));
        $response->assertStatus(200);

        $responseViewContent = $response->getContent();

          // プロフィール画像の表示を検証。
          // viewのタグ名等でマッチングさせ、該当箇所のimgタグにURL('storage'もURL内に)がセットされて
          // いれば、画像が表示されているとして検証する
        $pattern = '/<div class="content-input">\n.*?\n.*?<div class="user-image">\n.*?<img src="http:.*?storage.*?> \n/';
        preg_match_all($pattern, $responseViewContent, $matches);
        $targetResponse = $matches[0] ?? null;

        $this->assertNotEmpty($targetResponse);

          // ユーザー名の検証。当画面ではinputタグに表示されるため、viewレンダリングの表示箇所特定は不向き。
          // (不向きな理由：inputタグであればデータの表示有無に関わらず存在してしまうため)
          // databaseのデータがレスポンスとして適切に渡されていることを検証。
        $responseData = $response->original->getData();
        $responseUserData = $responseData['user']->toArray();

        $databaseUserData = User::find($user_id)->toArray();

        $this->assertEquals($databaseUserData['name'], $responseUserData['name']);

          // 郵便番号の検証。ユーザー名同様、inputタグのためデータにて検証。
        $this->assertEquals($databaseUserData['post_code'], $responseUserData['post_code']);

          // 住所の検証。同上。
        $this->assertEquals($databaseUserData['address'], $responseUserData['address']);

          // 建物名の検証。同上。
        $this->assertEquals($databaseUserData['building'], $responseUserData['building']);

    }
}
