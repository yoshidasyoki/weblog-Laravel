@extends('default.layouts')

@section('title', '記事閲覧')

@section('content')
    <header class="bg-cyan-400 px-28 py-5 flex flex-wrap justify-between">
        <div>
            <h1 class="text-white font-bold text-3xl">投稿ページ</h1>
        </div>
        <nav class="flex items-center space-x-1.5">
            <a href="{{ route('home') }}" class="nav-btn">TOPへ</a>
            {{-- パスの修正必要 --}}
            <a href="{{ route('register') }}" class="nav-btn">編集する</a>
            <a href="{{ route('logout') }}" class="nav-btn">ログアウト</a>
        </nav>
    </header>

    <div class="flex flex-col items-center justify-center">
        <div class="card w-200 my-6 px-12 py-10 space-y-6">
            <h1 class="text-article-header font-bold text-3xl">フルスクラッチ開発完了！</h1>

            <div class="text-md text-darkgray-700">
                <p>投稿者：{{ $article->user->username }}</p>
                <div class="flex space-x-6">
                    <p>投稿日：{{ $article->created_at->format('Y-m-d') }}</p>
                    <p>最終更新日：{{ $article->updated_at->format('Y-m-d') }}</p>
                </div>
            </div>

            <p class="whitespace-pre-wrap">{{ $article->body }}</p>
        </div>
    </div>
@endsection
