@if (session()->has('success'))
    <div class="message">
        <i class="fa-solid fa-check text-green-600 pr-6"></i>
        <p class="text-green-900 text-lg">{{ session()->get('success') }}</p>
    </div>
@endif
