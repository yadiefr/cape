@extends('layouts.siswa')

@section('title', 'Detail PKL')

@section('main-content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Detail Praktik Kerja Lapangan</h2>
                <a href="{{ route('siswa.pkl') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Informasi Perusahaan</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="mb-2"><span class="font-medium">Nama Perusahaan:</span> {{ $pkl->nama_perusahaan }}</p>
                        <p class="mb-2"><span class="font-medium">Alamat:</span> {{ $pkl->alamat_perusahaan }}</p>
                        <p class="mb-2"><span class="font-medium">Bidang Usaha:</span> {{ $pkl->bidang_usaha }}</p>
                        <p class="mb-2"><span class="font-medium">Pembimbing:</span> {{ $pkl->nama_pembimbing }}</p>
                        @if($pkl->telepon_pembimbing)
                            <p class="mb-2"><span class="font-medium">Telepon Pembimbing:</span> {{ $pkl->telepon_pembimbing }}</p>
                        @endif
                        @if($pkl->email_pembimbing)
                            <p class="mb-2"><span class="font-medium">Email Pembimbing:</span> {{ $pkl->email_pembimbing }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Status PKL</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="mb-2">
                            <span class="font-medium">Status:</span> 
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($pkl->status == 'pengajuan') bg-yellow-100 text-yellow-800
                                @elseif($pkl->status == 'berlangsung') bg-blue-100 text-blue-800
                                @elseif($pkl->status == 'selesai') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($pkl->status) }}
                            </span>
                        </p>
                        <p class="mb-2"><span class="font-medium">Tanggal Mulai:</span> {{ $pkl->tanggal_mulai->format('d M Y') }}</p>
                        <p class="mb-2"><span class="font-medium">Tanggal Selesai:</span> {{ $pkl->tanggal_selesai->format('d M Y') }}</p>
                        
                        @if($pkl->status === 'selesai')
                            <div class="mt-4 pt-4 border-t">
                                <h4 class="font-medium mb-2">Nilai:</h4>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                        <p class="text-sm text-gray-600">Teknis</p>
                                        <p class="text-xl font-bold">{{ $pkl->nilai_teknis ?? '-' }}</p>
                                    </div>
                                    <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                        <p class="text-sm text-gray-600">Sikap</p>
                                        <p class="text-xl font-bold">{{ $pkl->nilai_sikap ?? '-' }}</p>
                                    </div>
                                    <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                        <p class="text-sm text-gray-600">Laporan</p>
                                        <p class="text-xl font-bold">{{ $pkl->nilai_laporan ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-4">Dokumen</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-medium mb-2">Laporan PKL</h4>
                            @if($pkl->status !== 'pengajuan')
                                @if($pkl->dokumen_laporan)
                                    <div class="flex items-center">
                                        <a href="{{ route('siswa.pkl.laporan.download', $pkl->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mr-2">
                                            <i class="fas fa-download mr-2"></i>Download
                                        </a>
                                        @if($pkl->status === 'berlangsung')
                                            <form action="{{ route('siswa.pkl.laporan.upload', $pkl->id) }}" method="POST" enctype="multipart/form-data" class="flex-grow">
                                                @csrf
                                                <div class="flex items-center">
                                                    <input type="file" name="dokumen_laporan" accept=".pdf,.doc,.docx" class="flex-grow">
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 ml-2">
                                                        <i class="fas fa-upload mr-2"></i>Upload
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    @if($pkl->status === 'berlangsung')
                                        <form action="{{ route('siswa.pkl.laporan.upload', $pkl->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="flex items-center">
                                                <input type="file" name="dokumen_laporan" accept=".pdf,.doc,.docx" class="flex-grow">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 ml-2">
                                                    <i class="fas fa-upload mr-2"></i>Upload
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <p class="text-gray-500">Belum ada dokumen laporan</p>
                                    @endif
                                @endif
                            @else
                                <p class="text-gray-500">Upload laporan dapat dilakukan saat PKL berlangsung</p>
                            @endif
                        </div>

                        <div>
                            <h4 class="font-medium mb-2">Surat Keterangan Selesai</h4>
                            @if($pkl->surat_keterangan)
                                <a href="{{ route('siswa.pkl.surat.download', $pkl->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    <i class="fas fa-download mr-2"></i>Download
                                </a>
                            @else
                                <p class="text-gray-500">Belum ada surat keterangan</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($pkl->keterangan)
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-4">Keterangan Tambahan</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">{{ $pkl->keterangan }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
