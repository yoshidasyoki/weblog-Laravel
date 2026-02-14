@extends('default.layouts')
@section('title', 'weblog')

@section('content')
    <x-header title="ゴミ箱">
    </x-header>

    <main>
        <div class="flex flex-col items-center w-full">
            <x-message></x-message>

            <div class="w-full px-60 pt-12">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-2xl">削除記事一覧</h2>
                    <x-sort-form :addOptions="['deleted_at' => '削除日時']" :sort="$sort">
                    </x-sort-form>
                </div>

                @if ($articles->count() === 0)
                    <p class="text-lg">該当記事はありません。</p>
                @endif

                @foreach ($articles as $article)
                    <div class="card mb-3 h-30 mx-8 px-8 justify-center">
                        <div class="flex items-center my-2">
                            <a href="{{ route('articles.rummage', ['article' => $article->id]) }}"
                                class="text-xl font-bold cursor-pointer">
                                {{ $article->title }}
                            </a>
                        </div>
                        <div class="flex flex-wrap justify-between items-center space-y-0">
                            <p class="w-full text-sm text-red-500">
                                削除日：{{ $article->deleted_at->format('Y-m-d') }}
                            </p>
                            <p class="text-sm text-darkgray-700">
                                投稿日：{{ $article->created_at->format('Y-m-d') }} /
                                更新日：{{ $article->updated_at->format('Y-m-d') }}
                            </p>
                            <div class="mr-3 text-xl">
                                <form action="{{ route('articles.restore', ['article' => $article->id]) }}", method="post">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="hover:cursor-pointer">
                                        <i class="fa-regular fa-share-from-square"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="my-7">
                {{ $articles->onEachSide(1)->links() }}
            </div>
        </div>

    </main>
@endsection
