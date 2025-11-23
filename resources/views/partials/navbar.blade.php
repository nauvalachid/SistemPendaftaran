{{-- Navigation Bar --}}
<header class="flex justify-between items-center py-6">

    {{-- Logo --}}
    <a href="/">
        <img src="{{ asset('storage/logosd.png') }}" alt="Logo SD Muhammadiyah 2 Ambarketawang" class="h-14 w-auto">
    </a>

    {{-- Menu Links --}}
    <nav class="hidden md:flex items-center space-x-8 text-primaryblue font-semilight">
        <a href="{{ route('home') }}" class="hover:text-hover hover:font-bold transition-all duration-200">Beranda</a>

        <a href="#tentang-kami" class="hover:text-hover hover:font-bold transition-all duration-200">Tentang Kami</a>
        <a href="#" class="hover:text-hover hover:font-bold transition-all duration-200">Ekstrakurikuler</a>
        <a href="#" class="hover:text-hover hover:font-bold transition-all duration-200">Tenaga Pengajar</a>

        <a href="{{ route('pendaftaran.index') }}"
            class="flex items-center hover:text-hover hover:font-bold transition-all duration-200">
            Pendaftaran
            <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </a>
    </nav>

    {{-- Auth Buttons --}}
    <div class="flex items-center space-x-2">
        @if (Route::has('login'))
            @auth
                {{-- Tombol Logout --}}
                <a href="#" id="logoutButton"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-primaryblue rounded-full hover:bg-blue-950 transition-colors">
                    Logout
                </a>
            @else
                @if (Route::has('register'))
                    {{-- Tombol Sign Up --}}
                    <a href="{{ route('register') }}"
                        class="px-5 py-2 text-sm font-semibold text-white bg-primaryblue rounded-full hover:bg-hover transition-colors">
                        Sign Up
                    </a>
                @endif
                {{-- Tombol Sign In --}}
                <a href="{{ route('login') }}"
                    class="px-5 py-2 text-sm font-semibold text-white bg-primaryblue rounded-full hover:bg-hover transition-colors">
                    Sign In
                </a>
            @endauth
        @endif
    </div>
    {{-- Import file JS logout khusus untuk user yang login --}}
    @auth
        @vite('resources/js/logout.js')
    @endauth
</header>