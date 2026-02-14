<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;

class ArticleController extends Controller
{
    // ログインユーザーの投稿記事一覧を表示
    public function index(Request $request)
    {
        $sort = [
            'value' => $request->query('sort', 'created_at'),
            'order' => $request->query('order', 'desc'),
        ];

        $this->authorize('viewAny', Article::class);
        $articles = Article::where('user_id', Auth::user()->id)
            ->orderBy($sort['value'], $sort['order'])
            ->paginate(5);

        $params = [
            'articles' => $articles,
            'sort' => $sort,
        ];

        return view('article.index', $params);
    }

    // 記事の新規作成画面を表示
    public function create()
    {
        $this->authorize('create', Article::class);
        return view("article.create");
    }

    // 新規記事の投稿処理
    public function store(ArticleRequest $request)
    {
        $this->authorize('create', Article::class);
        $form = $request->validated();
        $form['user_id'] = Auth::user()->id;

        $article = new Article();
        $article->fill($form)->save();

        return to_route('home')->with('success', '投稿が完了しました');
    }

    // 選択された記事の表示
    public function show(string $id)
    {
        $article = Article::findOrFail($id);
        $this->authorize('view', $article);
        return view('article.show', ['article' => $article]);
    }

    // 記事の編集画面
    public function edit(string $id)
    {
        $article = Article::findOrFail($id);
        $this->authorize('update', $article);

        return view('article.edit', ['article' => $article]);
    }

    // 記事の更新処理
    public function update(ArticleRequest $request, string $id)
    {
        $form = $request->validated();
        $article = Article::findOrFail($id);
        $this->authorize('update', $article);

        $article->update($form);
        return to_route('articles.index')->with('success', '更新が完了しました');
    }

    // 論理削除処理
    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);
        $this->authorize('delete', $article);
        $article->delete();

        return to_route('articles.index')->with('success', '削除しました');
    }

    // 物理削除処理
    public function forceDelete(string $id)
    {
        $article = Article::onlyTrashed()
            ->findOrFail($id);
        $this->authorize('forceDelete', $article);

        $article->forceDelete();
        return to_route('articles.trash')->with('success', 'ゴミ箱から削除しました');
    }

    // ゴミ箱一覧を表示
    public function trash(Request $request)
    {
        $sort = [
            'value' => $request->query('sort', 'created_at'),
            'order' => $request->query('order', 'desc'),
        ];

        $this->authorize('viewAny', Article::class);
        $articles = Article::onlyTrashed()
            ->where('user_id', Auth::user()->id)
            ->orderBy($sort['value'], $sort['order'])
            ->paginate(5);

        $params = [
            'articles' => $articles,
            'sort' => $sort,
        ];

        return view('article.trash', $params);
    }

    // ゴミ箱内で選択されたファイルを表示
    public function rummage(string $id)
    {
        $article = Article::onlyTrashed()
            ->findOrFail($id);
        $this->authorize('restore', $article);
        return view('article.show', ['article' => $article]);
    }

    // ゴミ箱データの復元処理
    public function restore(string $id)
    {
        $article = Article::onlyTrashed()
            ->findOrFail($id);
        $this->authorize('restore', $article);
        $article->restore();

        return to_route('articles.trash')->with('success', '復元が完了しました');
    }
}
