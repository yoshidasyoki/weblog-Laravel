<?php

namespace Tests\Feature\Article;

use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadTest extends TestCase
{
    use RefreshDatabase;

    private array $publicArticle = [
        'title' => '公開記事',
        'body' => 'これは公開記事です',
        'is_public' => 1
    ];

    private array $privateArticle = [
        'title' => '非公開記事',
        'body' => 'これは非公開記事です',
        'is_public' => 0
    ];

    private array $userInfo = [
        'email' => 'oneself@email',
        'password' => 'password'
    ];

    private array $otherInfo = [
        'email' => 'other@email',
        'password' => 'password'
    ];

    // 公開記事は全ユーザー（未ログイン含む）で閲覧可能であることを確認
    public function test_access_permitted_public_article_to_all_user(): void
    {
        // ※ 公開記事の投稿処理(テストの前準備)
        $user = $this->createUser($this->userInfo);
        $other = $this->createUser($this->otherInfo);
        $article = $this->createArticle($user->id, $this->publicArticle);

        // 未ログインユーザーが閲覧可能であることを確認
        $response = $this->get(route('articles.show', ['article' => $article->id]));
        $response->assertStatus(200);

        // 投稿者本人が閲覧可能であることを確認
        $this->actingAs($user);
        $response = $this->get(route('articles.show', ['article' => $article->id]));
        $response->assertStatus(200);

        // ログイン済みの他人ユーザーが閲覧可能であることを確認
        $this->post(route('logout'));
        $this->actingAs($other);
        $response = $this->get(route('articles.show', ['article' => $article->id]));
        $response->assertStatus(200);
    }

    // 非公開記事は投稿者のみ閲覧可能であることを確認
    public function test_access_authorized_private_article_to_all_user(): void
    {
        // ※ 公開記事の投稿処理(テストの前準備)
        $user = $this->createUser($this->userInfo);
        $other = $this->createUser($this->otherInfo);
        $article = $this->createArticle($user->id, $this->privateArticle);

        // 未ログイン状態では閲覧を拒否されることを確認
        $response = $this->get(route('articles.show', ['article' => $article->id]));
        $response->assertStatus(403);

        // 投稿者本人のアクセスでは閲覧できることを確認
        $this->actingAs($user);
        $response = $this->get(route('articles.show', ['article' => $article->id]));
        $response->assertStatus(200);

        // 投稿者と異なるログイン済ユーザーは閲覧できないことを確認
        $this->post(route('logout'));
        $this->actingAs($other);
        $response = $this->get(route('articles.show', ['article' => $article->id]));
        $response->assertStatus(403);
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
        return Article::factory()->create([
            'user_id' => $userId,
            'title' => $article['title'],
            'body' => $article['body'],
            'is_public' => $article['is_public']
        ]);
    }
}
