<aside class="flex h-screen w-64 flex-col overflow-y-auto border-r bg-white px-5 py-8">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-x-4">
        <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 text-indigo-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
        </span>
        <div>
            {{-- Mengambil nama admin secara dinamis dari Auth --}}
            <h2 class="text-base font-semibold text-gray-800">{{ Auth::guard('admin')->user()->nama ?? 'Admin' }}</h2>
            <p class="text-sm text-gray-500">Admin</p>
        </div>
    </a>

    <div class="my-6 border-t border-gray-200"></div>

    <div class="flex flex-1 flex-col justify-between">
        <nav class="-mx-3 space-y-2">
            
            {{-- Dashboard Link --}}
            @php $isActive = request()->routeIs('admin.dashboard'); @endphp
            <a class="flex transform items-center rounded-lg px-3 py-2 transition-colors duration-300
                @if ($isActive) 
                    text-indigo-700 bg-indigo-50 font-semibold 
                @else 
                    text-gray-500 hover:bg-gray-100 hover:text-gray-700 
                @endif" 
                href="{{ route('admin.dashboard') }}" 
                @if ($isActive) aria-current="page" @endif>
                
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                <span class="mx-4 text-sm font-medium">Dashboard</span>
            </a>
            
            {{-- Pendaftaran Link --}}
            @php $isActive = request()->routeIs('admin.pendaftaran.index'); @endphp
            <a class="flex transform items-center rounded-lg px-3 py-2 transition-colors duration-300
                @if ($isActive) 
                    text-indigo-700 bg-indigo-50 font-semibold 
                @else 
                    text-gray-500 hover:bg-gray-100 hover:text-gray-700 
                @endif" 
                href="{{ route('admin.pendaftaran.index') }}" 
                @if ($isActive) aria-current="page" @endif>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                    <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.035-.84-1.875-1.875-1.875h-.75zM9.75 8.625c-1.035 0-1.875.84-1.875 1.875v11.25c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V10.5c0-1.035-.84-1.875-1.875-1.875h-.75zM3 15.375c-1.035 0-1.875.84-1.875 1.875v4.5c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875v-4.5c0-1.035-.84-1.875-1.875-1.875H3z" />
                </svg>
                <span class="mx-4 text-sm font-medium">Pendaftaran</span>
            </a>

          {{-- Konten Link --}}
            @php 
                $isActive = request()->routeIs('admin.konten.index*'); 
            @endphp

            <a class="flex transform items-center rounded-lg px-3 py-2 transition duration-300
                @if ($isActive) 
                    text-indigo-700 bg-indigo-50 font-semibold 
                @else 
                    text-gray-500 hover:bg-gray-100 hover:text-gray-700 
                @endif"
                href="{{ route('admin.konten.index') }}"
                @if ($isActive) aria-current="page" @endif>

                {{-- Ikon Pensil / Edit Konten --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 18.257V21.75H14.25l10.435-10.435zM16.862 4.487l1.688 1.688M10.582 18.257v-2.652a1.875 1.875 0 011.875-1.875h2.652" />
                </svg>

                <span class="mx-4 text-sm font-medium">Konten</span>
            </a>

        </nav>
        
        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();"
               class="flex transform items-center rounded-lg px-3 py-2 text-gray-500 transition-colors duration-300 hover:bg-red-100 hover:text-red-700">
                
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                <span class="mx-4 text-sm font-medium">Logout</span>
            </a>
        </form>
    </div>
</aside>
