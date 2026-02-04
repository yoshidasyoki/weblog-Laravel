@extends('default.layouts')

@section('title', 'ログイン')

@section('content')
    <div class="flex justify-center items-center h-screen">
        <div class="card justify-center items-center space-y-2 h-130 w-120">
            <h1 class="text-sky-400 font-bold text-3xl">ログイン</h1>

            <div class="w-80 scpace-y-3">
                @if ($errors->any())
                    <div
                        class="mx-auto my-4 w-full rounded-md border border-red-300 bg-red-50 px-4 py-2 text-center text-sm text-red-700">
                        <p class="font-bold">ログインに失敗しました</p>
                        <p class="text-sm"></p>{{ $errors->first('login') }}</p>
                    </div>
                @endif

                <form action="{{ route('login.auth') }}" method="post">
                    @csrf
                    <div class="flex flex-col gap-1">
                        <label for="email" class="text-sky-800">メールアドレス：</label>
                        <input id="email" type="text" name="email" value="{{ old('email') }}"
                            class="bg-gray-100 rounded px-3 py-1.5 border border-sky-300 focus:border-sky-300 focus:outline-none focus:border-2">

                        @if ($errors->has('email'))
                            <p class="text-red-500 text-sm">※ {{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="password" class="text-sky-800">パスワード：</label>
                        <input id="password" type="password" name="password"
                            class="bg-gray-100 rounded px-3 py-1.5 border border-sky-300 focus:border-sky-300 focus:outline-none focus:border-2">
                        @if ($errors->has('password'))
                            <p class="text-red-500 text-sm">※ {{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <button
                        class="mx-auto block bg-sky-400 hover:bg-sky-500 w-full py-2 text-white font-bold rounded-md cursor-pointer my-5"
                        type="submit">ログイン</button>
                </form>

                <div class="flex justify-between items-center text-sm text-sky-700">
                    <a href="{{ route('home') }}" class="flex items-center gap-1 hover:underline transition">
                        ← Topへ戻る
                    </a>

                    <a href="{{ route('register') }}" class="font-semibold hover:underline transition">
                        新規登録はこちら
                    </a>
                </div>
            </div>

        </div>

    </div>
@endsection


</html>
