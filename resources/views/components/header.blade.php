<header class="bg-cyan-400 px-28 h-16 flex justify-between items-center gap-20">
    <div class="flex items-end gap-5">
        <h2 class="text-white font-bold text-2xl">{{ $title }}</h2>
        {{ $slot }}
    </div>

    <nav class="h-full flex items-stretch">
        <a href="{{ route('home') }}"
            class="nav-link
                @if (request()->routeIs('home')) bg-cyan-200 @endif">
            <i class="fa-regular fa-house mr-1"></i> Top
        </a>
        @auth
            <a href="{{ route('articles.create') }}"
                class="nav-link
                @if (request()->routeIs('articles.create')) bg-cyan-200 @endif">
                <i class="fa-solid fa-pencil mr-1"></i> 投稿
            </a>
            <a href="{{ route('articles.index') }}"
                class="nav-link
                @if (request()->routeIs('articles.index')) bg-cyan-200 @endif">
                <i class="fa-regular fa-pen-to-square mr-1"></i> 編集
            </a>
            <a href="{{ route('articles.trash') }}"
                class="nav-link
                @if (request()->routeIs('articles.trash')) bg-cyan-200 @endif">
                <i class="fa-regular fa-trash-can mr-1"></i> ゴミ箱
            </a>
            <a href="{{ route('logout') }}" class="nav-link nav-link-danger">
                <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i>ログアウト
            </a>
        @else
            <a href="{{ route('login') }}"
                class="nav-link
                @if (request()->routeIs('login')) bg-cyan-200 @endif">
                <i class="fa-solid fa-arrow-right-to-bracket mr-1"></i> ログイン
            </a>
            <a href="{{ route('register') }}"
                class="nav-link
                @if (request()->routeIs('register')) bg-cyan-200 @endif">
                <i class="fa-regular fa-user mr-1"></i> 新規登録
            </a>
        @endauth
    </nav>
</header>
