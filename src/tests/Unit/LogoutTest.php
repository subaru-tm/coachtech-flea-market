<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_logoutCheck()
    {
        // 本テストケースでのテストユーザーを作成。
        $user = User::create([
            'name' => 'testuser',
            'email' => 'test@test.com',
            'password' => Hash::make('testpass'),
        ]);

        // 1. ユーザーにログインする
        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'testpass',
        ]);

          // 正常なログイン処理を確認（ステータス、リダイレクト先、ログイン状態）
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertTrue(Auth::check());


        // 2. ログアウトボタンを押す
        $response = $this->get(route('logout'));

          // ログアウト処理が実行されたことを検証（ステータス、リダイレクト先、ログインされていない状態）
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertFalse(Auth::check());
    }
}
