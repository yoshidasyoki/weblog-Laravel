@props(['options', 'route', 'sort'])

<form action="{{ request()->url() }}" method="get" class="js-pulldown flex justify-end items-center gap-3">
    <select name="sort" class="border-2 border-gray-500 rounded px-1 py-0.5">
        @foreach ($options as $option => $item)
            <option value="{{ $option }}" {{ $sort['value'] === $option ? 'selected' : '' }}>
                {{{ $item }}}
            </option>
        @endforeach
    </select>

    <div class="w-50 flex gap-3">
        <div>
            <input type="radio" id="desc" name="order" value="desc"
                {{ $sort['order'] === 'desc' ? 'checked' : '' }} />
            <label for="desc" class="cursor-pointer">
                {{ ($sort['value'] === 'title') ? '降順' : '新しい順' }}
            </label>
        </div>
        <div>
            <input type="radio" id="asc" name="order" value="asc"
                {{ $sort['order'] === 'asc' ? 'checked' : '' }} />
            <label for="asc" class="cursor-pointer">
                {{ ($sort['value'] === 'title') ? '昇順' : '古い順' }}
            </label>
        </div>
    </div>
</form>
