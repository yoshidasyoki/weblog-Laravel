@extends('default.layouts')
@section('title', 'weblog')

@section('content')
    {{-- <header class="bg-cyan-400 px-28 h-16 flex justify-center items-center gap-20">
        <div class="flex items-end gap-5">
            <h2 class="text-white font-bold text-2xl">Topページ</h2>
            <p class="text-white">ようこそ、ゲストさん！</p>
        </div>

        <nav class="h-full flex items-stretch">
            <a href="{{ route('home') }}" class="nav-link
                @if ($request->routeIs('home')) bg-cyan-300 @endif">
                <i class="fa-regular fa-house mr-1"></i> Top
            </a>
            <a href="{{ route('articles.create') }}" class="nav-link
                @if ($request->routeIs('articles.create')) bg-cyan-300 @endif">
                <i class="fa-solid fa-arrow-up-from-bracket mr-1"></i> 投稿
            </a>
            <a href="{{ route('articles.index') }}" class="nav-link
                @if ($request->routeIs('articles.index')) bg-cyan-300 @endif">
                <i class="fa-regular fa-pen-to-square mr-1"></i> 編集
            </a>
            <a href="{{ route('articles.trash') }}" class="nav-link
                @if ($request->routeIs('articles.trash')) bg-cyan-300 @endif">
                <i class="fa-regular fa-trash-can mr-1"></i> ゴミ箱
            </a>
            <a href="" class="nav-link nav-link-danger">
                ログアウト
            </a>
        </nav>
    </header> --}}


    <x-header title="Topページ">
        @auth
            <p class="text-white">ようこそ、{{ $user->username }}さん！</p>
        @else
            <p class="text-white">ようこそ、ゲストさん！</p>
        @endauth
    </x-header>

    <main>
        <div class="flex flex-col items-center w-full mb-10">
            <x-message></x-message>

            <div class="w-full px-60 pt-12">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-2xl">記事一覧</h2>
                    {{-- <form action="{{ request()->url() }}" method="get" class="js-pulldown flex justify-end items-center gap-3">
                        <select name="sort" class="border-2 border-gray-500 rounded px-1 py-0.5">
                            <option value="created_at" {{ $sort['value'] === 'created_at' ? 'selected' : '' }}>投稿日順
                            </option>
                            <option value="updated_at" {{ $sort['value'] === 'updated_at' ? 'selected' : '' }}>更新日順
                            </option>
                            <option value="title" {{ $sort['value'] === 'title' ? 'selected' : '' }}>タイトル順
                            </option>
                        </select>

                        <div class="w-50 flex gap-3">
                            @if ($sort['value'] === 'title')
                                <div>
                                    <input type="radio" id="asc" name="order" value="asc"
                                        {{ $sort['order'] === 'asc' ? 'checked' : '' }} />
                                    <label for="asc" class="cursor-pointer">昇順</label>
                                </div>
                                <div>
                                    <input type="radio" id="desc" name="order" value="desc"
                                        {{ $sort['order'] === 'desc' ? 'checked' : '' }} />
                                    <label for="desc" class="cursor-pointer">降順</label>
                                </div>
                            @else
                                <div>
                                    <input type="radio" id="desc" name="order" value="desc"
                                        {{ $sort['order'] === 'desc' ? 'checked' : '' }} />
                                    <label for="desc" class="cursor-pointer">新しい順</label>
                                </div>
                                <div>
                                    <input type="radio" id="asc" name="order" value="asc"
                                        {{ $sort['order'] === 'asc' ? 'checked' : '' }} />
                                    <label for="asc" class="cursor-pointer">古い順</label>
                                </div>
                            @endif
                        </div>
                    </form> --}}

                    {{-- @php
                        $options = [
                            'created_at' => '投稿日順',
                            'updated_at' => '更新日順',
                            'title' => 'タイトル順',
                            'deleted_at' => '削除日時',
                        ];
                    @endphp --}}
                    {{-- <x-sort-form :items="$items"></x-sort-form> --}}
                    {{-- <x-sort-form :options="$options" :route="route('home')"></x-sort-form> --}}
                    <x-sort-form :sort="$sort"></x-sort-form>
                </div>

                @if ($articles->count() === 0)
                    <p class="text-lg">投稿記事がまだありません。</p>
                @endif

                @foreach ($articles as $article)
                    <a href="{{ route('articles.show', ['article' => $article->id]) }}"
                        class="card mb-3 h-30 mx-8 px-8 justify-center space-y-2 cursor-pointer">
                        <h3 class="text-xl font-bold">{{ $article->title }}</h3>
                        <p class="text-sm text-darkgray-700">
                            投稿者：{{ $article->user->username }} /
                            投稿日：{{ $article->created_at->format('Y-m-d') }} /
                            更新日：{{ $article->updated_at->format('Y-m-d') }}
                        </p>
                    </a>
                @endforeach
            </div>

            <div class="my-7">
                {{ $articles->onEachSide(1)->links() }}
            </div>
        </div>
    </main>
@endsection
