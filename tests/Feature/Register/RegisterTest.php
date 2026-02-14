<?php

namespace Tests\Feature\Register;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    use RefreshDatabase;

    // 新規ユーザーの登録処理テスト
    public function test_register_new_user_process(): void
    {
        // ※ 登録ページへアクセス(テストの前準備)
        $this->get(route('register'));

        // 登録処理が正常に行えることを確認
        $response = $this->post(route('register.create'), [
            'username' => 'username',
            'email' => 'test@mail',
            'password' => 'password'
        ]);
        $response->assertRedirect(route('login'));

        // 既に登録済みのアカウントで入力したときには失敗することを確認
        $response = $this->post(route('register.create'), [
            'username' => 'username',
            'email' => 'test@mail',
            'password' => 'password'
        ]);
        $response->assertRedirect(route('register'));

        // 入力不備の際に失敗することを確認
        $response = $this->post(route('register.create'), [
            'username' => 'abcd',
            'email' => 'abcd@mail',
            'password' => ''
        ]);
        $response->assertRedirect(route('register'));
    }
}
