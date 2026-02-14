@extends('default.layouts')

@section('title', '記事の新規投稿')

@section('content')
    <x-header title="編集ページ">
    </x-header>

    <main>
        <div class="flex justify-center">
            <x-message></x-message>

            <div class="w-150 my-14 text-md">
                <form action="{{ route('articles.update', ['article' => $article->id]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="space-y-7">
                        <div class="flex flex-col gap-1">
                            <label for="title" class="text-sky-800 text-lg font-bold">タイトル</label>
                            <input id="title" type="text" name="title" value="{{ old('title', $article->title) }}"
                                class="bg-white rounded px-3 py-2 border border-sky-300 focus:border-sky-300 focus:outline-none focus:border-2">

                            @if ($errors->has('title'))
                                <p class="text-red-500 text-md">※ {{ $errors->first('title') }}</p>
                            @endif
                        </div>

                        <div class="flex flex-col gap-1">
                            <label for="body" class="text-sky-800 text-lg font-bold">本文</label>
                            <textarea name="body" id="body"
                                class="bg-white rounded px-3 py-2 h-60 border border-sky-300 focus:border-sky-300 focus:outline-none focus:border-2">{{ old('body', $article->body) }}</textarea>

                            @if ($errors->has('body'))
                                <p class="text-red-500 text-md">※ {{ $errors->first('body') }}</p>
                            @endif
                        </div>

                        <div class="">
                            <p class="text-sky-800 text-lg font-bold">公開設定</label>
                            <div
                                class="flex flex-col justify-center bg-white rounded px-6 py-5 border border-sky-300 space-y-3 mb-1">
                                <label class="radio-option block cursor-pointer">
                                    <input type="radio" name="is_public" value="1"
                                        {{ old('is_public', $article->is_public) == '1' ? 'checked' : '' }}>
                                    <span>公開する</span>
                                </label>

                                <label class="radio-option block cursor-pointer">
                                    <input type="radio" name="is_public" value="0"
                                        {{ old('is_public', $article->is_public) == '0' ? 'checked' : '' }}>
                                    <span>非公開にする</span>
                                </label>
                            </div>
                            @if ($errors->has('is_public'))
                                <p class="text-red-500 text-md">※ {{ $errors->first('is_public') }}</p>
                            @endif
                        </div>

                        <div class="flex justify-center">
                            <button
                                class="mx-auto block bg-sky-400 hover:bg-sky-500 w-80 py-2 text-white font-bold rounded-md cursor-pointer my-5"
                                type="submit">更新する
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </main>
@endsection
