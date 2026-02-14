<?php

namespace Tests\Feature\Article;

use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    // テスト用のダミーデータを定義
    private array $userInfo = [
        'username' => 'test',
        'email' => 'test@mail',
        'password' => 'password'
    ];

    private array $article = [
        'title' => 'テスト投稿',
        'body' => 'テスト投稿です',
        'is_public' => '0'
    ];

    // 投稿ページへのアクセス権テスト（ログイン状態でアクセスした場合）
    public function test_access_permission_with_logged_in_state(): void
    {
        // ※ ユーザーの作成・ログイン処理(テストの前準備)
        $user = $this->createUser($this->userInfo);
        $this->actingAs($user);

        // ログイン状態では投稿ページへ移動できることを確認
        $response = $this->get(route('articles.create'));
        $response->assertStatus(200);

        // ログイン状態では記事を投稿できることを確認
        $response = $this->post(route('articles.store'), $this->article);
        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('articles', $this->article);
    }

    public function test_access_denied_with_logged_out(): void
    {
        // 未ログイン状態では投稿ページへ移動できないことを確認
        $response = $this->get(route('articles.create'));
        $response->assertRedirect(route('login'));

        // 未ログイン状態では記事を投稿できないことを確認
        $response = $this->post(route('articles.store'), $this->article);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('articles', $this->article);
    }

    private function createUser(array $userInfo): User
    {
        return User::factory()->create([
            'email' => $userInfo['email'],
            'password' => Hash::make($userInfo['password'])
        ]);
    }
}
