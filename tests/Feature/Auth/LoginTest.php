<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private array $createInput = [
        'username' => 'test',
        'email' => 'test@mail',
        'password' => 'password'
    ];

    private array $missingInput = [
        'email' => 'test@mail',
        'password' => 'missingPass'
    ];

    public function test_login_process(): void
    {
        // ※ テスト用アカウントを作成&ログインページへアクセス(テストの前準備)
        $user = $this->createUser($this->createInput);
        $this->get(route('login'));

        // メールorパスワード誤り時はログインに失敗することを確認
        $response = $this->post(route('login'), [
            'email' => $this->missingInput['email'],
            'password' => $this->missingInput['password']
        ]);
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        // ユーザー情報登録済であればログインできることを確認
        $response = $this->post(route('login'), [
            'email' => 'test@mail',
            'password' => 'password'
        ]);
        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    // ログアウト処理テスト
    public function test_logout_process(): void
    {
        // ※ ユーザー作成＆ログイン処理(テストの前準備)
        $user = $this->createUser($this->createInput);
        $this->actingAs($user);

        // ログアウト処理の確認
        $response = $this->get(route('logout'));
        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    private function createUser(array $input):User
    {
        return User::factory()->create([
            'username' => $input['username'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
