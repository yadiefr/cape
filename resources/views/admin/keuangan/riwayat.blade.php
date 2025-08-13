@extends('layouts.admin')

@section('title', 'Riwayat Pembayaran Siswa')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-8 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Riwayat Pembayaran Siswa</h1>
        <a href="{{ route('admin.keuangan.index') }}" class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-gray-600 hover:bg-gray-700">
            Kembali
        </a>
    </div>
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:space-x-8">
            <div>
                <p class="font-semibold text-gray-700 text-lg">Nama Siswa:</p>
                <p class="text-xl text-gray-900">{{ $siswa->nama_lengkap ?? $siswa->nama }}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-700 text-lg">Kelas:</p>
                <p class="text-xl text-gray-900">{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Tambahan: Rincian Tagihan Siswa --}}
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Rincian Tagihan Siswa</h2>
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-base">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Nama Tagihan</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Nominal</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Total Dibayar</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Sisa</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($detailTagihan as $tagihan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $tagihan['nama_tagihan'] }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($tagihan['nominal'],0,',','.') }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($tagihan['total_dibayar'],0,',','.') }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($tagihan['sisa'],0,',','.') }}</td>
                        <td class="px-6 py-4">
                            @if($tagihan['status'] == 'Lunas')
                                <span class="px-3 py-1.5 rounded-full bg-green-100 text-green-800 text-sm font-medium">Lunas</span>
                            @elseif($tagihan['status'] == 'Sebagian')
                                <span class="px-3 py-1.5 rounded-full bg-yellow-100 text-yellow-800 text-sm font-medium">Sebagian</span>
                            @else
                                <span class="px-3 py-1.5 rounded-full bg-red-100 text-red-800 text-sm font-medium">Belum Lunas</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($tagihan['status'] != 'Lunas')
                                <form action="{{ route('admin.keuangan.bayar', $tagihan['id']) }}" method="POST" class="flex items-center space-x-3">
                                    @csrf
                                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                    <input type="hidden" name="tanggal" value="{{ now()->format('Y-m-d H:i:s') }}">
                                    <span class="text-sm text-gray-600 whitespace-nowrap">{{ now()->format('d/m/Y H:i') }}</span>
                                    <input type="number" name="jumlah" class="form-input w-32 rounded-md border-gray-300 text-base" placeholder="Jumlah" required min="1" max="{{ $tagihan['sisa'] }}">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-base font-medium">Bayar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 text-base">Tidak ada tagihan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Pembayaran</h2>
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Tanggal & Jam</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Keterangan</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Jumlah</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($siswa->pembayaran as $pembayaran)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-base text-gray-900">
                            <div>{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y') }}</div>
                            <small class="text-gray-500">{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('H:i:s') }}</small>
                        </td>
                        <td class="px-6 py-4 text-base text-gray-900">{{ $pembayaran->keterangan }}</td>
                        <td class="px-6 py-4 text-base text-green-700 font-semibold">Rp {{ number_format($pembayaran->jumlah,0,',','.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $pembayaran->status == 'Lunas' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">{{ $pembayaran->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 text-base">Belum ada pembayaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection