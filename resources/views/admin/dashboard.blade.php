@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Halo, {{ Auth::guard('admin')->user()->nama ?? 'Admin' }}!</h1>
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                Selamat datang di Admin Dashboard Sistem Pendaftaran.
                Di sini Anda dapat memantau dan mengelola seluruh proses pendaftaran, konten, dan pengguna sistem.
            </div>
        </div>

        {{-- Contoh Kotak Ringkasan Data --}}
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
            
            {{-- Total Pendaftaran --}}
            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-5">
                <div class="text-sm font-medium text-gray-500 truncate">Total Pendaftaran Masuk</div>
                <div class="mt-1 text-3xl font-semibold text-indigo-600">
                    {{-- Ganti dengan data count dari Controller --}}
                    {{ '125' }}
                </div>
                <a href="{{ route('admin.pendaftaran.index') }}" class="text-sm text-indigo-500 hover:text-indigo-700 mt-2 block">Lihat Detail</a>
            </div>

            {{-- Pendaftaran Belum Diverifikasi --}}
            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-5">
                <div class="text-sm font-medium text-gray-500 truncate">Perlu Diverifikasi</div>
                <div class="mt-1 text-3xl font-semibold text-orange-500">
                    {{ '25' }} 
                </div>
                <span class="text-sm text-gray-500 mt-2 block">Menunggu Tindakan TU/Admin</span>
            </div>
            
            {{-- Pengguna Aktif --}}
            <div class="bg-white overflow-hidden shadow sm:rounded-lg p-5">
                <div class="text-sm font-medium text-gray-500 truncate">Pengguna Terdaftar</div>
                <div class="mt-1 text-3xl font-semibold text-green-600">
                    {{ '450' }}
                </div>
                <span class="text-sm text-gray-500 mt-2 block">Total Akun User</span>
            </div>
        </div>
        
    </div>
</div>
@endsection