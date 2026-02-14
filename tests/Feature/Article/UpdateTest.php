<?php

namespace Tests\Feature\Article;

use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    // テスト用のダミーデータを定義
    private array $userInfo = [
        'email' => 'oneself@email',
        'password' => 'password'
    ];

    private array $otherInfo = [
        'email' => 'other@email',
        'password' => 'password'
    ];

    private array $article = [
        'title' => 'テスト投稿',
        'body' => 'これはテスト投稿です',
        'is_public' => 1
    ];

    private array $updateArticle = [
        'title' => '【更新】テスト投稿',
        'body' => '投稿を更新しました',
        'is_public' => 0
    ];

    // 編集ページへのアクセス権テスト（投稿者本人がアクセスした場合）
    public function test_access_permitted_with_oneself_account(): void
    {
        // ※ アカウント作成・ログインと記事の投稿処理(テストの前準備)
        $user = $this->createUser($this->userInfo);
        $this->actingAs($user);
        $article = $this->createArticle($user->id, $this->article);

        // ログイン状態で自分の投稿記事の編集を選択 → 編集ページへ遷移できることを確認
        $response = $this->get(route('articles.edit', ['article' => $article->id]));
        $response->assertStatus(200);

        // 自分の投稿記事であれば更新可能であることを確認
        $response = $this->updateArticle($article->id, $this->updateArticle);
        $response->assertRedirect(route('articles.index'));
        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => $this->updateArticle['title'],
            'body' => $this->updateArticle['body'],
            'is_public' => $this->updateArticle['is_public']
        ]);
    }

    // 編集ページへのアクセス権テスト（他人ユーザーがアクセスした場合）
    public function test_access_denied_with_other_account(): void
    {
        // ※ 投稿者本人のアカウント作成＆記事の投稿処理(テストの前準備)
        $user = $this->createUser($this->userInfo);
        $article = $this->createArticle($user->id, $this->article);

        // ※ 他人ユーザーのアカウント作成＆ログイン(テストの前準備)
        $other = $this->createUser($this->otherInfo);
        $this->actingAs($other);

        // 他人の記事を編集しようと試みる → アクセスが拒否されることを確認
        $response = $this->get(route('articles.edit', ['article' => $article->id]));
        $response->assertStatus(403);

        // 他人の投稿記事は更新できないことを確認
        $response = $this->updateArticle($article->id, $this->updateArticle);
        $response->assertStatus(403);
        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => $this->article['title'],
            'body' => $this->article['body'],
            'is_public' => $this->article['is_public']
        ]);
    }

    // 編集ページへのアクセス権テスト（未ログイン状態でアクセスした場合）
    public function test_access_denied_with_logged_out(): void
    {
        // ※ 投稿者本人のアカウント作成＆記事の投稿処理(テストの前準備)
        $user = $this->createUser($this->userInfo);
        $article = $this->createArticle($user->id, $this->article);

        // ※ 未ログイン状態であることを確認(テストの前準備)
        $this->assertGuest();

        // 記事を編集しようと試みる → アクセスが拒否されることを確認
        $response = $this->get(route('articles.edit', ['article' => $article->id]));
        $response->assertRedirect(route('login'));

        // 記事の更新ができないことを確認
        $response = $this->updateArticle($article->id, $this->updateArticle);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => $this->article['title'],
            'body' => $this->article['body'],
            'is_public' => $this->article['is_public']
        ]);
    }

    private function createUser(array $userInfo): User
    {
        return User::factory()->create([
            'email' => $userInfo['email'],
            'password' => Hash::make($userInfo['password'])
        ]);
    }

    private function createArticle(int $userId, array $article): Article
    {
        // サンプル記事を作成
        $article = Article::factory()->create([
            'user_id' => $userId,
            'title' => $article['title'],
            'body' => $article['body'],
            'is_public' => $article['is_public']
        ]);

        // 記事の存在を確認
        $this->assertDatabaseHas($article);

        return $article;
    }

    private function updateArticle(int $articleId, array $updateArticle)
    {
        return $this->put(route('articles.update', ['article' => $articleId]), [
            'title' => $updateArticle['title'],
            'body' => $updateArticle['body'],
            'is_public' => $updateArticle['is_public']
        ]);
    }
}
