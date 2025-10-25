@extends('layouts.app')
    
    <div class="flex h-screen">
        <!-- Sidebar -->
    <x-sidebar />

        <main class="w-full overflow-y-auto p-8 lg:p-12">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="mt-2 text-gray-600">Selamat datang di Dashboard Admin!</p>
            </div>

            <hr class="my-8 h-px border-0 bg-gray-200">

            <div class="rounded-xl border bg-white p-6 shadow-lg">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Pendaftar Periode 2025/2026</h2>
                    <a href="#" class="flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        Detail
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="ml-1 h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="flex items-center gap-x-4">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6"><path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM5.25 15.375a3.75 3.75 0 100 7.5 3.75 3.75 0 000-7.5zM15 15.375a3.75 3.75 0 100 7.5 3.75 3.75 0 000-7.5z"></path></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">17</p>
                            <p class="text-sm text-gray-600">Total Pendaftar</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-x-4">
                         <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-700">
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6"><path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06l6.22-6.22H3a.75.75 0 010-1.5h16.19l-6.22-6.22a.75.75 0 010-1.06z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06l6.22-6.22H3a.75.75 0 010-1.5h16.19l-6.22-6.22a.75.75 0 010-1.06z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">9</p>
                            <p class="text-sm text-gray-600">Diterima</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-x-4">
                         <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-700">
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">5</p>
                            <p class="text-sm text-gray-600">Ditolak</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-x-4">
                         <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 text-yellow-700">
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">3</p>
                            <p class="text-sm text-gray-600">Pending</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>

</body>
</html>