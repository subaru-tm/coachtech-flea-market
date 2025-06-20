<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     /**
     * @test
     * @dataProvider dataproviderValidation
     */

    public function loginValidationCheck(array $keys, array $values, array $messages, bool $expect)
    {
        $dataList = array_combine($keys, $values);

        $request = new LoginRequest;
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
            'メールアドレスが入力されていない場合、「メールアドレスを入力してください」を表示' => [
                ['email', 'password'],
                [null, 'testpass'],
                ['email' => ['メールアドレスを入力してください']],
                false
            ],
            'パスワードが入力されていない場合、「パスワードを入力してください」を表示' => [
                ['email', 'password'],
                ['test@test.com', null],
                ['password' => ['パスワードを入力してください']],
                false
            ],
        ];
    }

    public function test_loginErrorCheck()
    {
        // 「ログイン情報が登録されていません」のエラーメッセージは、
        // 上記のdataproviderでは検証できないため別途検証。
        // formRequestでは設定できないバリデーションと解釈し、
        // formRequestを通った後にコントローラでエラー判定、メッセージ格納しています。

        $response = $this->post('/login', [
            'email' => 'aaa@com',
            'password' => 'testpass',
        ]);

        $response->assertRedirect('/login');
        $response->assertStatus(302);


        $errors = Session::get('errors');
        $this->assertArrayHasKey('email', $errors->toArray());

        $this->assertEquals('ログイン情報が登録されていません', $errors->get('email')[0]);

    }

    public function test_loginCheck()
    {
        // 本テストケース用のテストユーザーを作成。
        $user = User::create([
            'name' => 'testuser',
            'email' => 'test@test.com',
            'password' => Hash::make('testpass'),
        ]);

        // 1. ログインページを開き、2. 全ての必要高温句を入力の上、ログインボタンを押す
        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'testpass',
        ]);

        $response->assertStatus(302);

        // ホーム画面に遷移し、ログインされていることを検証。
        $response->assertRedirect('/');
        $this->assertTrue(Auth::check());

    }

}
