@extends('layouts.app-ppdb')

@section('title', 'Edit Pendaftaran PPDB - SMK PGRI CIKAMPEK')

@section('content')
<div class="ppdb-card">
    <!-- Header -->
    <div class="ppdb-section-header">
        <h2 class="text-center mb-0">Edit Formulir Pendaftaran</h2>
    </div>

    <div class="mt-4">
        <div class="ppdb-title">Edit Pendaftaran PPDB</div>
        <p class="ppdb-subtitle">
            SMK PGRI CIKAMPEK - Tahun Ajaran {{ $ppdb_year }}
        </p>
        
        <div class="text-center mb-4">
            <span class="badge bg-primary px-3 py-2 rounded-pill fw-normal d-inline-flex align-items-center">
                <i class="fas fa-calendar-alt me-2"></i>
                Periode: {{ \Carbon\Carbon::parse($ppdb_start_date)->format('d F Y') }} - {{ \Carbon\Carbon::parse($ppdb_end_date)->format('d F Y') }}
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
        <i class="fas fa-check-circle me-3 fa-lg"></i>
        <div>
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
        <div>
            {{ session('error') }}
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger mb-4" role="alert">
        <div class="d-flex mb-2">
            <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
            <div class="fw-bold">Mohon periksa kembali formulir:</div>
        </div>
        <ul class="ms-4 mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('ppdb.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Data Pribadi -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary bg-gradient text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-circle fa-lg me-2"></i>
                    <h5 class="mb-0">Data Pribadi Siswa</h5>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-user"></i></span>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pendaftaran->nama_lengkap) }}" required
                                class="form-control @error('nama_lengkap') is-invalid @enderror">
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            NISN <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-id-card"></i></span>
                            <input type="text" name="nisn" value="{{ old('nisn', $pendaftaran->nisn) }}" required
                                class="form-control @error('nisn') is-invalid @enderror"
                                pattern="[0-9]{10}" maxlength="10">
                            @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-hint">Masukkan 10 digit NISN</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Tempat Lahir <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir) }}" required
                                class="form-control @error('tempat_lahir') is-invalid @enderror">
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Tanggal Lahir <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-calendar"></i></span>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pendaftaran->tanggal_lahir->format('Y-m-d')) }}" required
                                class="form-control @error('tanggal_lahir') is-invalid @enderror">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Jenis Kelamin <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-venus-mars"></i></span>
                            <select name="jenis_kelamin" required
                                class="form-control form-select @error('jenis_kelamin') is-invalid @enderror">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Agama <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-pray"></i></span>
                            <select name="agama" required
                                class="form-control form-select @error('agama') is-invalid @enderror">
                                <option value="">Pilih Agama</option>
                                <option value="Islam" {{ old('agama', $pendaftaran->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama', $pendaftaran->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama', $pendaftaran->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama', $pendaftaran->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama', $pendaftaran->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama', $pendaftaran->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                            @error('agama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            No. Telepon <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-phone"></i></span>
                            <input type="tel" name="telepon" value="{{ old('telepon', $pendaftaran->telepon) }}" required
                                class="form-control @error('telepon') is-invalid @enderror"
                                placeholder="Contoh: 08123456789">
                            @error('telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Email
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email', $pendaftaran->email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="email@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            Alamat Lengkap <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-home"></i></span>
                            <textarea name="alamat" rows="2" required
                                class="form-control @error('alamat') is-invalid @enderror"
                                placeholder="Masukkan alamat lengkap">{{ old('alamat', $pendaftaran->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            Asal Sekolah <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-school"></i></span>
                            <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $pendaftaran->asal_sekolah) }}" required
                                class="form-control @error('asal_sekolah') is-invalid @enderror"
                                placeholder="Masukkan nama sekolah asal">
                            @error('asal_sekolah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Data Orang Tua -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info bg-gradient text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-users fa-lg me-2"></i>
                    <h5 class="mb-0">Data Orang Tua/Wali</h5>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            Nama Ayah <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-user"></i></span>
                            <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $pendaftaran->nama_ayah) }}" required
                                class="form-control @error('nama_ayah') is-invalid @enderror">
                            @error('nama_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Nama Ibu <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-user"></i></span>
                            <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $pendaftaran->nama_ibu) }}" required
                                class="form-control @error('nama_ibu') is-invalid @enderror">
                            @error('nama_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Pekerjaan Ayah
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-briefcase"></i></span>
                            <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $pendaftaran->pekerjaan_ayah) }}"
                                class="form-control @error('pekerjaan_ayah') is-invalid @enderror">
                            @error('pekerjaan_ayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Pekerjaan Ibu
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-briefcase"></i></span>
                            <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $pendaftaran->pekerjaan_ibu) }}"
                                class="form-control @error('pekerjaan_ibu') is-invalid @enderror">
                            @error('pekerjaan_ibu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            No. HP Orang Tua <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-phone"></i></span>
                            <input type="tel" name="telepon_orangtua" value="{{ old('telepon_orangtua', $pendaftaran->telepon_orangtua) }}" required
                                class="form-control @error('telepon_orangtua') is-invalid @enderror"
                                placeholder="Contoh: 08123456789">
                            @error('telepon_orangtua')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            Alamat Orang Tua
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-home"></i></span>
                            <textarea name="alamat_orangtua" rows="2"
                                class="form-control @error('alamat_orangtua') is-invalid @enderror"
                                placeholder="Kosongkan jika sama dengan alamat siswa">{{ old('alamat_orangtua', $pendaftaran->alamat_orangtua) }}</textarea>
                            @error('alamat_orangtua')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pilihan Jurusan -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success bg-gradient text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-graduation-cap fa-lg me-2"></i>
                    <h5 class="mb-0">Pilihan Jurusan</h5>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <label class="form-label">
                            Jurusan yang Dipilih <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-list"></i></span>
                            <select name="pilihan_jurusan_1" required
                                class="form-control form-select @error('pilihan_jurusan_1') is-invalid @enderror">
                                <option value="">Pilih Jurusan</option>
                                @foreach($jurusan as $j)
                                    <option value="{{ $j->id }}" {{ old('pilihan_jurusan_1', $pendaftaran->pilihan_jurusan_1) == $j->id ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pilihan_jurusan_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Nilai Ujian -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-warning bg-gradient text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-chart-bar fa-lg me-2"></i>
                    <h5 class="mb-0">Nilai Ujian</h5>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">
                            Matematika
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-calculator"></i></span>
                            <input type="number" name="nilai_matematika" value="{{ old('nilai_matematika', $pendaftaran->nilai_matematika) }}"
                                min="0" max="100" step="0.01"
                                class="form-control @error('nilai_matematika') is-invalid @enderror"
                                placeholder="0-100">
                            <span class="input-group-text">/100</span>
                            @error('nilai_matematika')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Bahasa Indonesia
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-book"></i></span>
                            <input type="number" name="nilai_indonesia" value="{{ old('nilai_indonesia', $pendaftaran->nilai_indonesia) }}"
                                min="0" max="100" step="0.01"
                                class="form-control @error('nilai_indonesia') is-invalid @enderror"
                                placeholder="0-100">
                            <span class="input-group-text">/100</span>
                            @error('nilai_indonesia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Bahasa Inggris
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-language"></i></span>
                            <input type="number" name="nilai_inggris" value="{{ old('nilai_inggris', $pendaftaran->nilai_inggris) }}"
                                min="0" max="100" step="0.01"
                                class="form-control @error('nilai_inggris') is-invalid @enderror"
                                placeholder="0-100">
                            <span class="input-group-text">/100</span>
                            @error('nilai_inggris')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submit -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0 d-flex align-items-center">
                        <div class="me-2">
                            <i class="fas fa-info-circle text-primary fa-lg"></i>
                        </div>
                        <div>
                            <span class="text-secondary">
                                <span class="text-danger fw-bold">*</span> Wajib diisi
                            </span>
                        </div>
                    </div>                    <div class="d-flex flex-column flex-md-row gap-3">
                        <a href="{{ route('pendaftar.dashboard') }}" class="btn btn-secondary-ppdb">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .form-label {
        font-weight: 500;
        color: #555;
    }
    
    .form-hint {
        font-size: 0.8rem;
        color: #888;
        margin-top: 0.25rem;
    }
    
    .card-header {
        border-radius: 8px 8px 0 0 !important;
        border: none;
    }
    
    .card {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .btn-secondary-ppdb {
        background: #f8f9fa;
        border: 1px solid #ddd;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 5px;
        transition: all 0.3s;
        color: #444;
    }
    
    .btn-secondary-ppdb:hover {
        background: #e9ecef;
        color: #333;
    }
</style>
@endpush

@endsection
