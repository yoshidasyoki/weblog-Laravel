<?php

namespace Tests\Feature\Article;

use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTest extends TestCase
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

    // ゴミ箱ページへのアクセス権テスト（未ログイン状態でアクセスした場合）
    public function test_access_denied_with_logged_out(): void
    {
        // ※ 論理投稿された記事の作成(テストの前準備)
        $user = $this->createUser($this->userInfo);
        $article = $this->createArticle($user->id, $this->article);
        $deletedArticle = $this->createSoftDeletedArticle($user->id, $this->article);

        // ゴミ箱ページへアクセスできないことを確認
        $response = $this->get(route('articles.trash'));
        $response->assertRedirect(route('login'));

        // 論理削除を試みるとログインページへ遷移するか確認
        $response = $this->delete(route('articles.destroy', ['article' => $article->id]));
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas($article);

        // 削除記事の閲覧を試みるとログインページへ遷移することを確認
        $response = $this->get(route('articles.rummage', ['article' => $deletedArticle->id]));
        $response->assertRedirect(route('login'));

        // 削除記事の復元を試みるとログインページへ遷移することを確認
        $response = $this->patch(route('articles.restore', ['article' => $deletedArticle->id]));
        $response->assertRedirect(route('login'));
        $this->assertSoftDeleted($deletedArticle);

        // 物理削除を試みるとログインページへ遷移することを確認
        $response = $this->delete(route('articles.forceDelete', ['article' => $deletedArticle->id]));
        $response->assertRedirect(route('login'));
        $this->assertSoftDeleted($deletedArticle);
    }

    // ゴミ箱ページへのアクセス権テスト（投稿者本人がアクセスした場合）
    public function test_access_permitted_with_oneself_account(): void
    {
        // ※ アカウント作成・ログインと投稿記事の作成(テストの前準備)
        $user = $this->createUser($this->userInfo);
        $this->actingAs($user);
        $article = $this->createArticle($user->id, $this->article);

        // 論理削除できることを確認
        $this->get(route('articles.index'));
        $response = $this->delete(route('articles.destroy', ['article' => $article->id]));
        $response->assertRedirect(route('articles.index'));
        $this->assertSoftDeleted($article);

        // ゴミ箱ページへ移動できることを確認
        $response = $this->get(route('articles.trash'));
        $response->assertStatus(200);

        // 論理削除した記事を閲覧できることを確認
        $response = $this->get(route('articles.rummage', ['article' => $article->id]));
        $response->assertStatus(200);

        // 論理削除した記事を復元できることを確認
        $this->get(route('articles.trash'));
        $response = $this->patch(route('articles.trash', ['article' => $article->id]));
        $response->assertRedirect(route('articles.trash'));
        $this->assertDatabaseHas($article);

        // 論理削除した記事を物理削除できることを確認
        $article->delete();
        $this->delete(route('articles.forceDelete', ['article' => $article->id]));
        $response->assertRedirect(route('articles.trash'));
        $this->assertDatabaseMissing($article);
    }

    public function test_access_denied_with_other_account(): void
    {
        // ※ 論理投稿された記事の作成(テストの前準備)
        $user = $this->createUser($this->userInfo);
        $article = $this->createArticle($user->id, $this->article);
        $deletedArticle = $this->createSoftDeletedArticle($user->id, $this->article);

        // ※ アカウントの作成・ログイン処理(テストの前準備)
        $other = $this->createUser($this->otherInfo);
        $this->actingAs($other);

        // ゴミ箱ページへはアクセスできることを確認
        $response = $this->get(route('articles.trash'));
        $response->assertStatus(200);

        // 他人の記事を論理削除できないことを確認
        $response = $this->delete(route('articles.destroy', ['article' => $article->id]));
        $response->assertStatus(403);
        $this->assertDatabaseHas($article);

        // 他人の削除記事は閲覧できないことを確認
        $response = $this->get(route('articles.rummage', ['article' => $deletedArticle->id]));
        $response->assertStatus(403);

        // 他人の削除記事は復元できないことを確認
        $response = $this->patch(route('articles.restore', ['article' => $deletedArticle->id]));
        $response->assertStatus(403);
        $this->assertSoftDeleted($deletedArticle);

        // 他人の記事を物理削除できないことを確認
        $response = $this->delete(route('articles.forceDelete', ['article' => $deletedArticle->id]));
        $response->assertStatus(403);
        $this->assertSoftDeleted($deletedArticle);
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

    private function createSoftDeletedArticle(int $userId, array $article): Article
    {
        // サンプル記事を作成
        $article = Article::factory()->trashed()->create([
            'user_id' => $userId,
            'title' => $article['title'],
            'body' => $article['body'],
            'is_public' => $article['is_public']
        ]);

        // 記事の存在を確認
        $this->assertSoftDeleted($article);

        return $article;
    }
}
