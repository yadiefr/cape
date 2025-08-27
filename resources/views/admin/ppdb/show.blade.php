@extends('layouts.admin')

@section('title', 'Detail Pendaftaran PPDB - SMK PGRI CIKAMPEK')

@section('main-content')
<div class="container px-3 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Detail Pendaftaran PPDB</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('admin.ppdb.dashboard') }}" class="hover:text-blue-600">PPDB</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('admin.ppdb.index') }}" class="hover:text-blue-600">Data Pendaftaran</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Detail</span>
                </li>
            </ul>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center flex-wrap">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Informasi Pendaftaran</h2>
                <p class="text-sm text-gray-500">{{ $pendaftaran->nomor_pendaftaran }}</p>
            </div>
            
            <div class="flex flex-wrap gap-2 mt-2 sm:mt-0">
                <a href="{{ route('admin.ppdb.edit', $pendaftaran->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('pendaftaran.print', ['nomor' => $pendaftaran->nomor_pendaftaran, 'nisn' => $pendaftaran->nisn]) }}" target="_blank" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                    <i class="fas fa-print mr-2"></i> Cetak
                </a>
                <form action="{{ route('admin.ppdb.destroy', $pendaftaran->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin menghapus data pendaftaran ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                        <i class="fas fa-trash mr-2"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Status Pendaftaran -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <h3 class="text-md font-semibold text-gray-800 mr-3">Status Pendaftaran:</h3>
                    <span class="px-3 py-1 text-sm rounded-full 
                        {{ $pendaftaran->status == 'menunggu' ? 'bg-yellow-100 text-yellow-800' : 
                        ($pendaftaran->status == 'diterima' ? 'bg-green-100 text-green-800' : 
                        ($pendaftaran->status == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                        {{ ucfirst($pendaftaran->status) }}
                    </span>
                </div>
                
                <!-- Form Update Status -->
                <form action="{{ route('admin.ppdb.status', $pendaftaran->id) }}" method="POST" class="bg-gray-50 p-4 rounded-lg">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Ubah Status</label>
                            <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="menunggu" {{ $pendaftaran->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="diterima" {{ $pendaftaran->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="ditolak" {{ $pendaftaran->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="cadangan" {{ $pendaftaran->status == 'cadangan' ? 'selected' : '' }}>Cadangan</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ $pendaftaran->keterangan }}</textarea>
                        </div>
                    </div>
                    
                    <div class="mt-3 text-right">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                            Perbarui Status
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Informasi Pendaftaran -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Pribadi -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-700">Data Pribadi</h3>
                    </div>
                    <div class="p-4">
                        <table class="w-full">
                            <!-- Info Akun -->
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top" width="35%">Status Akun</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: <span class="px-2 py-1 text-xs rounded-full {{ $pendaftaran->user ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $pendaftaran->user ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                            </tr>
                            @if($pendaftaran->user)
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top">Username</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->user->username }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top">Password</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: <span class="text-gray-500 italic">NISN siswa ({{ $pendaftaran->nisn }})</span></td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top">Email</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->user->email }}</td>
                            </tr>
                            @if($pendaftaran->user->last_login_at)
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top">Login Terakhir</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->user->last_login_at->format('d F Y H:i') }}</td>
                            </tr>
                            @endif
                            @endif
                            <!-- Data Siswa -->
                            <tr>
                                <td colspan="2" class="pt-4 pb-2">
                                    <div class="border-t border-gray-200"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top" width="35%">Nama Lengkap</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top">NISN</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->nisn }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top">Jenis Kelamin</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->jenis_kelamin }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top">Tempat, Tgl. Lahir</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->tempat_lahir }}{{ $pendaftaran->tanggal_lahir ? ', '.$pendaftaran->tanggal_lahir->format('d F Y') : '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top">Agama</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->agama }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top">Alamat</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->alamat }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top">Telepon</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->telepon }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top">Email</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-600 align-top">Asal Sekolah</td>
                                <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->asal_sekolah }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Data Orang Tua dan Pendaftaran -->
                <div>
                    <!-- Data Orang Tua -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-700">Data Orang Tua</h3>
                        </div>
                        <div class="p-4">
                            <table class="w-full">
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 align-top" width="35%">Nama Ayah</td>
                                    <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->nama_ayah }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 align-top">Nama Ibu</td>
                                    <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->nama_ibu }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 align-top">Pekerjaan Ayah</td>
                                    <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->pekerjaan_ayah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 align-top">Pekerjaan Ibu</td>
                                    <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->pekerjaan_ibu ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 align-top">Telepon Ortu</td>
                                    <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->telepon_orangtua ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 align-top">Alamat Ortu</td>
                                    <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->alamat_orangtua ?? 'Sama dengan alamat siswa' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Data Pendaftaran -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-700">Data Pendaftaran</h3>
                        </div>
                        <div class="p-4">
                            <table class="w-full">
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 align-top" width="35%">Tanggal Daftar</td>
                                    <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->tanggal_pendaftaran ? $pendaftaran->tanggal_pendaftaran->format('d F Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 align-top">Tahun Ajaran</td>
                                    <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->tahun_ajaran }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 align-top">Status</td>
                                    <td class="py-2 text-sm text-gray-900 align-top">: {{ ucfirst($pendaftaran->status) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 align-top">Pilihan Jurusan 1</td>
                                    <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->jurusanPertama->nama }}</td>
                                </tr>
                                @if($pendaftaran->nilai_matematika || $pendaftaran->nilai_indonesia || $pendaftaran->nilai_inggris)
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 align-top">Nilai UN</td>
                                    <td class="py-2 text-sm text-gray-900 align-top">
                                        <ul class="list-disc list-inside pl-2">
                                            @if($pendaftaran->nilai_matematika)
                                            <li>Matematika: {{ $pendaftaran->nilai_matematika }}</li>
                                            @endif
                                            @if($pendaftaran->nilai_indonesia)
                                            <li>B. Indonesia: {{ $pendaftaran->nilai_indonesia }}</li>
                                            @endif
                                            @if($pendaftaran->nilai_inggris)
                                            <li>B. Inggris: {{ $pendaftaran->nilai_inggris }}</li>
                                            @endif
                                        </ul>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="py-2 text-sm text-gray-600 align-top">Keterangan</td>
                                    <td class="py-2 text-sm text-gray-900 align-top">: {{ $pendaftaran->keterangan ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
