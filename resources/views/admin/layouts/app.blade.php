<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard | @yield('title', 'Sistem Pendaftaran')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        
        {{-- Navigation Bar (Sederhana) --}}
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center text-xl font-semibold text-gray-800">
                            Admin Panel
                        </div>
                        
                        {{-- Link Navigasi --}}
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                Dashboard
                            </x-nav-link>
                            <x-nav-link :href="route('admin.pendaftaran.index')" :active="request()->routeIs('admin.pendaftaran.*')">
                                Data Pendaftaran
                            </x-nav-link>
                            {{-- Tambah link lain di sini: Kelola Konten, Kelola TU --}}
                        </div>
                    </div>

                    {{-- Logout Form --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                                Log Out ({{ Auth::guard('admin')->user()->nama ?? 'Admin' }})
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>