@extends('admin.layouts.app') 
{{-- Menggunakan layout utama yang mencakup body tag, etc. --}}

@section('title', 'Daftar Pendaftaran Siswa')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar component -->
    <x-sidebar />

    <main class="w-full overflow-y-auto p-8 lg:p-12">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-100">
                <h2 class="text-3xl font-bold mb-6 text-gray-800">Manajemen Pendaftaran Siswa</h2>

                {{-- Pesan Sukses/Error --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="overflow-x-auto rounded-lg border">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Pendaftar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asal Sekolah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Daftar</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($pendaftarans as $pendaftaran)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $pendaftaran->id_pendaftaran }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pendaftaran->nama_siswa }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pendaftaran->user->email ?? 'N/A' }} 
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pendaftaran->asal_sekolah }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pendaftaran->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('admin.pendaftaran.show', $pendaftaran->id_pendaftaran) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 bg-gray-50 italic">Belum ada data pendaftaran yang masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $pendaftarans->links() }}
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
