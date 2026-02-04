<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;

class ArticleController extends Controller
{
    // ログインユーザーの投稿記事一覧を表示
    public function index()
    {
        $articles = Article::all()->where('user_id', Auth::user()->id);
        return view('article.index', ['articles' => $articles]);
    }

    // 記事の新規作成画面を表示
    public function create()
    {
        return view("article.create");
    }

    // 新規記事の投稿処理
    public function store(ArticleRequest $request)
    {
        $form = $request->validated();
        $form['user_id'] = Auth::user()->id;

        $article = new Article();
        $article->fill($form)->save();

        return to_route('home')->with('success', '投稿が完了しました');
    }

    // 選択された記事の表示
    public function show(string $id)
    {
        $article = Article::find($id);
        return view('article.show', ['article' => $article]);
    }

    // 記事の編集画面
    public function edit(string $id)
    {
        $article = Article::find($id);
        return view('article.edit', ['article' => $article]);
    }

    // 記事の更新処理
    public function update(ArticleRequest $request, string $id)
    {
        $form = $request->validated();
        $article = Article::find($id);

        $article->update($form);
        return to_route('article.index')->with('success', '更新が完了しました');
    }

    // 削除処理
    public function destroy(string $id)
    {
        Article::find($id)->delete();
        return to_route('articles.index')->with('success', '削除しました');
    }
}
