@extends('layouts.admin')

@section('title', 'Edit Data Siswa - SMK PGRI CIKAMPEK')

@section('main-content')
<!-- Page Content -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex flex-col space-y-6">
        <!-- Page Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 relative overflow-hidden">
            <div class="absolute right-0 -top-12 h-40 w-40 bg-blue-100 opacity-50 rounded-full"></div>
            <div class="absolute -right-8 top-20 h-20 w-20 bg-blue-200 opacity-30 rounded-full"></div>
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-user-edit text-blue-600 mr-3"></i>
                            Edit Data Siswa
                        </h1>
                        <p class="text-gray-600 mt-1">Edit data siswa: <span class="font-medium">{{ $siswa->nama_lengkap }}</span></p>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <a href="{{ route('admin.siswa.show', $siswa->id) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-5 py-2.5 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                        <a href="{{ route('admin.siswa.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>        <!-- Form Card -->        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-6">
            @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Ada beberapa kesalahan pada form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div class="border-b pb-4 mb-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-5">Data Pribadi</h2>
                              <div class="mb-6">
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="h-32 w-32 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-md">
                                        <img id="photo-preview" src="{{ $siswa->foto ? asset('storage/' . $siswa->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($siswa->nama_lengkap) . '&background=3b82f6&color=ffffff' }}" alt="Foto Siswa" class="h-full w-full object-cover">
                                    </div>
                                    <div class="w-full max-w-xs">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Siswa</label>
                                        <input type="file" name="foto" id="photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" onchange="previewImage()">
                                        <p class="mt-1 text-sm text-gray-500">JPG, PNG, atau GIF. Maks 2MB.</p>
                                    </div>
                                </div>
                            </div>
                              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">                                <div>
                                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-1">NIS (Nomor Induk Siswa)</label>
                                    <input type="text" name="nis" id="nis" value="{{ old('nis', $siswa->nis) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm {{ $errors->has('nis') ? 'border-red-300' : 'border-gray-300' }} rounded-md" placeholder="Masukkan NIS">
                                    @error('nis')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
                                    <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $siswa->nisn) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm {{ $errors->has('nisn') ? 'border-red-300' : 'border-gray-300' }} rounded-md" placeholder="Masukkan NISN">
                                    @error('nisn')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm {{ $errors->has('nama_lengkap') ? 'border-red-300' : 'border-gray-300' }} rounded-md" placeholder="Masukkan nama lengkap">
                                    @error('nama_lengkap')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full py-2 px-3 {{ $errors->has('jenis_kelamin') ? 'border-red-300' : 'border-gray-300' }} bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ $siswa->tempat_lahir }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Tempat lahir">
                                </div>
                                
                                <div>
                                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ $siswa->tanggal_lahir }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $siswa->email) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm {{ $errors->has('email') ? 'border-red-300' : 'border-gray-300' }} rounded-md" placeholder="contoh@email.com">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="agama" class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                                    <select name="agama" id="agama" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="Islam" {{ $siswa->agama == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen" {{ $siswa->agama == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                        <option value="Katolik" {{ $siswa->agama == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="Hindu" {{ $siswa->agama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Buddha" {{ $siswa->agama == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                        <option value="Konghucu" {{ $siswa->agama == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    </select>
                                </div>
                            </div>                            <div class="mt-4">
                                <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                <input type="tel" name="telepon" id="telepon" value="{{ $siswa->telepon }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="08xxxxxxxxxx">
                            </div>
                            
                            <div class="mt-4">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Alamat lengkap">{{ $siswa->alamat }}</textarea>
                            </div>
                        </div>
                        
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-5">Data Orang Tua</h2>
                              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nama_ayah" class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" id="nama_ayah" value="{{ $siswa->nama_ayah }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Nama ayah">
                                </div>
                                
                                <div>
                                    <label for="pekerjaan_ayah" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ayah</label>
                                    <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" value="{{ $siswa->pekerjaan_ayah }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Pekerjaan ayah">
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label for="no_telp_ayah" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon Ayah</label>
                                <input type="tel" name="no_telp_ayah" id="no_telp_ayah" value="{{ $siswa->no_telp_ayah }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="08xxxxxxxxxx">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="nama_ibu" class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" id="nama_ibu" value="{{ $siswa->nama_ibu }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Nama ibu">
                                </div>
                                
                                <div>
                                    <label for="pekerjaan_ibu" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ibu</label>
                                    <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" value="{{ $siswa->pekerjaan_ibu }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Pekerjaan ibu">
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label for="no_telp_ibu" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon Ibu</label>
                                <input type="tel" name="no_telp_ibu" id="no_telp_ibu" value="{{ $siswa->no_telp_ibu }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="08xxxxxxxxxx">
                            </div>

                            <div class="mt-4">
                                <label for="alamat_orangtua" class="block text-sm font-medium text-gray-700 mb-1">Alamat Orang Tua</label>
                                <textarea name="alamat_orangtua" id="alamat_orangtua" rows="2" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Alamat orang tua">{{ $siswa->alamat_orangtua }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div class="border-b pb-4 mb-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-5">Data Akademik</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">                                
                                <div>
                                    <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                                    <select name="jurusan_id" id="jurusan" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                        <option value="">Pilih Jurusan</option>
                                        @forelse($jurusan as $j)
                                            <option value="{{ $j->id }}" {{ old('jurusan_id', $siswa->jurusan_id) == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                                        @empty
                                            <option value="" disabled>Tidak ada data jurusan</option>
                                        @endforelse
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                                    <select name="kelas_id" id="kelas" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                        <option value="">Pilih Kelas</option>
                                        @forelse($kelas as $k)
                                            <option value="{{ $k->id }}" {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                        @empty
                                            <option value="" disabled>Tidak ada data kelas</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="tahun_masuk" class="block text-sm font-medium text-gray-700 mb-1">Tahun Masuk</label>
                                    <input type="number" name="tahun_masuk" id="tahun_masuk" value="{{ $siswa->tahun_masuk }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="2023">
                                </div>
                                
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="aktif" {{ $siswa->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="tidak_aktif" {{ $siswa->status == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        <option value="lulus" {{ $siswa->status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                        <option value="pindah" {{ $siswa->status == 'pindah' ? 'selected' : '' }}>Pindah</option>
                                    </select>
                                </div>                            </div>
                        </div>
                        
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-5">Reset Password</h2>
                            
                            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Informasi Reset Password</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>Isi form di bawah ini hanya jika Anda ingin me-reset password siswa. Biarkan kosong jika tidak ingin mengubah password.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                    <input type="password" name="password" id="password" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Masukkan password baru">
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Konfirmasi password baru">
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <div class="flex items-center">
                                    <input id="reset_default" name="reset_default" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="reset_default" class="ml-2 block text-sm text-gray-700">Reset ke password default (menggunakan tanggal lahir)</label>
                                </div>
                            </div>
                        </div>

                        <div class="pt-5 mt-6 border-t border-gray-200">
                            <div class="flex justify-end">
                                <button type="reset" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Reset
                                </button>
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage() {
        const file = document.getElementById('photo').files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
    
    document.getElementById('reset_default').addEventListener('change', function() {
        const passwordFields = document.getElementById('password');
        const confirmPasswordFields = document.getElementById('password_confirmation');
        
        if (this.checked) {
            passwordFields.disabled = true;
            confirmPasswordFields.disabled = true;
        } else {
            passwordFields.disabled = false;
            confirmPasswordFields.disabled = false;
        }
    });
</script>
@endsection
