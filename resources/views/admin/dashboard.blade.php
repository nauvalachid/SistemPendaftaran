@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <x-sidebar />

    <main class="w-full overflow-y-auto p-8 lg:p-12">
        <div>
            <h1 class="text-3xl font-bold text-black">Dashboard {{ Auth::guard('admin')->user()->nama ?? 'Admin' }}</h1>
            <p class="mt-2 text-black">Selamat datang di Dashboard Admin!</p>
        </div>

        <hr class="my-5 h-px border-0 bg-black">

        <div class="rounded-2xl bg-white p-6 shadow-md">
            <div class="flex justify-between items-center mb-4">
               @php
                    $tahunSekarang = date('Y');
                    $tahunBerikutnya = $tahunSekarang + 1;
                @endphp

                <h2 class="text-lg font-bold text-black">
                    Pendaftar Periode {{ $tahunSekarang }}/{{ $tahunBerikutnya }}
                </h2>

                <a href="{{ route('admin.pendaftaran.index') }}" class="text-sm text-gray-500 hover:text-gray-800">
                    Detail &gt;
                </a>
            </div>

            <div class="flex flex-wrap justify-center gap-40">
                <!-- Total Pendaftar -->
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('icons/people.svg') }}" alt="Icon People" class="w-20 h-20 object-contain">
                    <div>
                        <p class="text-2xl font-bold text-black">{{ $totalPendaftaran ?? 0 }}</p>
                        <p class="text-sm text-black">Total Pendaftar</p>
                    </div>
                </div>

                <!-- Diterima -->
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('icons/acc.svg') }}" alt="Icon Acc" class="w-20 h-20 object-contain">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $diterimaCount ?? 0 }}</p>
                        <p class="text-sm text-gray-700">Diterima</p>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('icons/decline.svg') }}" alt="Icon Decline" class="w-20 h-20 object-contain">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $ditolakCount ?? 0 }}</p>
                        <p class="text-sm text-gray-700">Ditolak</p>
                    </div>
                </div>

                <!-- Pending -->
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('icons/pending.svg') }}" alt="Icon Pending" class="w-20 h-20 object-contain">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingCount ?? 0 }}</p>
                        <p class="text-sm text-gray-700">Pending</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
