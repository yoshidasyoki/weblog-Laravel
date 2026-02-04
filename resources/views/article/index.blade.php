@extends('default.layouts')
@section('title', 'weblog')

@section('content')
    <header class="bg-cyan-400 px-28 py-5 flex flex-wrap justify-between">
        <div>
            <h1 class="text-white font-bold text-3xl">投稿ページ</h1>
        </div>
        <nav class="flex items-center space-x-1.5">
            <a href="" class="nav-btn flex items-center gap-2">
                <i class="fa-solid fa-trash-arrow-up"></i>
                ゴミ箱
            </a>
            <a href="{{ route('home') }}" class="nav-btn">TOPへ</a>
            <a href="{{ route('logout') }}" class="nav-btn">ログアウト</a>
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
                    <div class="card mb-3 h-30 mx-8 px-8 justify-center space-y-2">
                        <div class="flex items-center">
                            @if ($article->is_public == true)
                                <span
                                    class="bg-cyan-400 rounded text-white font-bold text-center text-sm mr-3 py-0.5 w-15">公開</span>
                            @elseif ($article->is_public == false)
                                <span
                                    class="bg-red-400 rounded text-white font-bold text-center text-sm mr-3 py-0.5 w-15">非公開</span>
                            @endif
                            <a href="{{ route('articles.show', ['article' => $article->id]) }}"
                                class="text-xl font-bold cursor-pointer">
                                {{ $article->title }}
                            </a>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-darkgray-700">
                                投稿日：{{ $article->created_at->format('Y-m-d') }} /
                                更新日：{{ $article->updated_at->format('Y-m-d') }}
                            </p>
                            <div class="flex gap-5 text-lg">
                                <a href="{{ route('articles.edit', ['article' => $article->id]) }}">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('articles.destroy', ['article' => $article->id]) }}", method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
