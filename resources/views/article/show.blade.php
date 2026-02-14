@extends('default.layouts')

@section('title', '記事閲覧')

@section('content')
    {{-- <x-header title="記事の閲覧">
        <a href="{{ route('home') }}" class="nav-btn">TOPへ</a>
        @if ($article->deleted_at)
            <form action="{{ route('articles.restore', ['article' => $article->id]) }}" method="post">
                @csrf
                @method('PATCH')
                <button type="submit"class="nav-btn">復元する</button>
            </form>
        @else
            <a href="{{ route('articles.edit', ['article' => $article->id]) }}" class="nav-btn">編集する</a>
        @endif
        <a href="{{ route('logout') }}" class="nav-btn">ログアウト</a>
    </x-header> --}}

    <x-header title="記事"></x-header>

    <div class="flex flex-col w-200 mx-auto justify-center">
        <div class="card my-6 px-12 py-10 space-y-6">
            <h2 class="text-article-header font-bold text-2xl">{{ $article->title }}</h2>

            <div class="text-md text-darkgray-700">
                <p>投稿者：{{ $article->user->username }}</p>
                <div class="flex space-x-6">
                    <p>投稿日：{{ $article->created_at->format('Y-m-d') }}</p>
                    <p>最終更新日：{{ $article->updated_at->format('Y-m-d') }}</p>
                </div>
            </div>

            <p class="whitespace-pre-wrap">{{ $article->body }}</p>
        </div>
        @if ($article->deleted_at)
            <div class="flex gap-5 w-full px-12">

                <form method="post" action="{{ route('articles.restore', ['article' => $article->id]) }}" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button
                        class="w-full bg-sky-400 hover:bg-sky-500 cursor-pointer  py-2 text-white font-bold rounded-md my-5">
                        復元する
                    </button>
                </form>
                <form method="post" action="{{ route('articles.forceDelete', ['article' => $article->id]) }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button
                        class="js-confirm w-full bg-darkgray-500 hover:bg-darkgray-700 cursor-pointer py-2 text-white font-bold rounded-md my-5">
                        完全削除する
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
