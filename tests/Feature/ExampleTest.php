<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get(route("home"));
        $response->assertStatus(200);
    }

    // 未ログイン状態でアクセス・操作を試みたときの挙動テスト
    public function test_access_page_or_operation_state_of_logged_out(): void
    {
        // ---- アクセス・操作が拒否されることの確認 ----
        //【投稿】
        // 記事の投稿しても保存されないことを確認
        $response = $this->post(route('articles.store'), [
            'title' => 'テスト投稿',
            'body' => 'これはテスト投稿です',
            'is_public' => '1'
        ]);
        $this->assertDatabaseMissing('articles', [
            'user_id' => 1,
            'title' => 'テスト投稿',
            'body' => 'これはテスト投稿です',
            'is_public' => '1'
        ]);
        $response->assertRedirect(route('login'));

        // ログインユーザーの投稿記事一覧ページへアクセスできないことを確認
        $response = $this->get(route('articles.index'));
        $response->assertRedirect(route('login'));

        // 記事の投稿ページへ遷移できないことを確認
        $response = $this->get(route('articles.create'));
        $response->assertRedirect(route('login'));

        //【編集】
        // 編集一覧ページ（ログインユーザーの投稿一覧ページ）へアクセスできないことを確認
        $response = $this->get(route('articles.index'));
        $response->assertRedirect(route('login'));

        // 記事の編集ページへ遷移できないことを確認
        // $article = Article::factory()->create([
        //     'user_id' => 1,
        //     'title' => 'テスト投稿',
        //     'body' => 'これはテスト投稿です',
        //     'is_public' => '1'
        // ]);
        // $response = $this->get(route('articles', ['article' => 1]));
    }

    public function test_access_page_or_operation_state_of_logged_in(): void
    {
        // ※ ログイン状態にする
        $user = User::factory()->create([
            'email' => 'test@mail',
            'password' => Hash::make('password')
        ]);
        $this->post(route('login'), [
            'email' => 'test@mail',
            'password' => 'password'
        ]);
        $this->assertAuthenticatedAs($user);    // ログイン状態であることを確認
    }


}
