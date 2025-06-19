<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterRequest;
use Tests\TestCase;

class RegisterTest extends TestCase
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

    public function registerValidationCheck(array $keys, array $values, array $messages, bool $expect)
    {
        $dataList = array_combine($keys, $values);

        // 会員登録ページを開く（ことと同等になるようにフォームリクエストを呼び出す）
        $request = new RegisterRequest;
        $rules = $request->rules();
        $validator = Validator::make($dataList, $rules);
        $validator = $validator->setCustomMessages($request->messages());
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
        $this->assertSame($messages, $validator->errors()->messages());
    }

    public function dataproviderValidation()
    {
        // 各項目のバリデーションメッセージ検証は、それぞれのデータにて検証
        return [
            '名前が入力されていない場合、「お名前を入力してください」を表示' => [
                ['name', 'email', 'password', 'password_confirmation'],
                [null, 'test@test.com', 'testpass', 'testpass'],
                ['name' => ['お名前を入力してください']],
                false
            ],
            'メールアドレスが入力されていない場合、「メールアドレスを入力してください」を表示' => [
                ['name', 'email', 'password', 'password_confirmation'],
                ['testuser', null, 'testpass', 'testpass'],
                ['email' => ['メールアドレスを入力してください']],
                false
            ],
            'パスワードが入力されていない場合、「パスワードを入力してください」を表示' => [
                ['name', 'email', 'password', 'password_confirmation'],
                ['testuser', 'test@test.com', null, 'testpass'],
                ['password' => ['パスワードを入力してください']],
                false
            ],
            'パスワードが7文字以下の場合、「パスワードは8文字以上で入力してください」を表示' => [
                ['name', 'email', 'password', 'password_confirmation'],
                ['testuser', 'test@test.com', 'test', 'test'],
                ['password' => ['パスワードは8文字以上で入力してください']],
                false
            ],
            'パスワードが確認用パスワードと一致しない場合、「パスワードと一致しません」を表示' => [
                ['name', 'email', 'password', 'password_confirmation'],
                ['testuser', 'test@test.com', 'testpass', 'passpass'],
                ['password' => ['パスワードと一致しません']],
                false
            ],
        ];
    }

    
    public function test_registerDatabaseCreateCheck()
    {
        // 1. 会員登録ページを開く、および
        // 2. 全ての入力項目を正しく入力する、そして 3. 登録ボタンを押す、を同時に実行。
        $response = $this->post('/register', [
            'name' => 'testuser',
            'email' => 'test@test.com',
            'password' => 'testpass',
            'password_confirmation' => 'testpass',
        ]);
        $response->assertStatus(302);
        // テストケース一覧の「期待挙動」欄には「ログイン画面に遷移する」との記載があったが、
        // 画面遷移図では、「プロフィール設定画面_初回ログイン時」への遷移となっていたため、
        // 後者を実装してテストとしています。
        $response->assertRedirect('/mypage/profile');
        $this->assertDatabaseHas('users',[
            'name' => 'testuser',
            'email' => 'test@test.com',
        ]);
    }
}
