@extends('default.layouts')
@section('title', 'weblog')

@section('content')
    <header class="bg-cyan-400 px-28 py-5 flex flex-wrap justify-between">
        <div>
            <h1 class="text-white font-bold text-3xl">Topページ</h1>
        </div>
        <nav class="flex items-center space-x-1.5">
            @if ($user)
                <p class="text-white font-bold pr-5">ようこそ、{{ $user->username }} さん！</p>
                <a href="{{ route('articles.create') }}" class="nav-btn">投稿する</a>
                <a href="{{ route('articles.index') }}" class="nav-btn">編集する</a>
                <a href="{{ route('logout') }}" class="nav-btn">ログアウト</a>
            @else
                <p class="text-white font-bold pr-5">ようこそ、ゲストさん！</p>
                <a href="{{ route('login') }}" class="nav-btn">ログイン</a>
                <a href="{{ route('register') }}" class="nav-btn">新規登録</a>
            @endif
        </nav>
    </header>

    <main>
        <div class="flex flex-col items-center w-full">
            @if (session()->has('success'))
                <div class="message">
                    <i class="fa-solid fa-check text-green-600 pr-6"></i>
                    <p class="text-green-900 text-lg">{{ session()->get('success') }}</p>
                </div>
            @endif

            <div class="w-full px-60 pt-12">
                <h2 class="font-bold pb-4 text-2xl">記事一覧</h2>

                @if (empty($articles))
                    <p class="text-lg">投稿記事がまだありません。</p>
                @endif

                @foreach ($articles as $article)
                    <a href="{{ route('articles.show', ['article' => $article->id]) }}" class="card mb-3 h-30 mx-8 px-8 justify-center space-y-2 cursor-pointer">
                        <h3 class="text-xl font-bold">{{ $article->title }}</h3>
                        <p class="text-sm text-darkgray-700">
                            投稿者：{{ $article->user->username }} /
                            投稿日：{{ $article->created_at->format('Y-m-d') }} /
                            更新日：{{ $article->updated_at->format('Y-m-d') }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </main>
@endsection
