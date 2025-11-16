<nav class="w-full bg-white shadow-md py-2 px-4 flex items-center">
    {{-- Logo SD --}}
    <div class="flex items-center">
        <img src="{{ $logo ?? asset('storage/logosd.png') }}" alt="Logo SD" class="w-70 h-12">
    </div>

    {{-- Bisa ditambah menu kanan --}}
    <div class="ml-auto flex items-center gap-4">
        {{ $slot }}
    </div>
</nav>
