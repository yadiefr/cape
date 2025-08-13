@extends('layouts.app-ppdb')

@section('title', 'Dashboard PPDB - SMK PGRI CIKAMPEK')

@section('content')
<div class="ppdb-card">
    <!-- Header -->
    <div class="ppdb-section-header">
        <h2 class="text-center mb-0">Dashboard PPDB</h2>
    </div>

    <div class="mt-4">
        <div class="ppdb-title">Informasi Pendaftaran</div>
        <p class="ppdb-subtitle">
            Tahun Ajaran {{ $ppdb_year }}
        </p>
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

    <!-- Status Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary bg-gradient text-white py-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-clipboard-check fa-lg me-2"></i>
                <h5 class="mb-0">Status Pendaftaran</h5>
            </div>
        </div>
        
        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="p-3 bg-light rounded border-start border-primary border-4">
                        <label class="fw-bold text-primary mb-1 small">Nomor Pendaftaran:</label>
                        <div class="fs-5 fw-semibold">{{ $pendaftaran->nomor_pendaftaran }}</div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    @if($pendaftaran->status == 'menunggu')
                    <div class="badge bg-warning text-dark w-100 py-3 fs-6 d-flex align-items-center justify-content-center">
                        <i class="fas fa-clock me-2"></i> Menunggu
                    </div>
                    @elseif($pendaftaran->status == 'diterima')
                    <div class="badge bg-success w-100 py-3 fs-6 d-flex align-items-center justify-content-center">
                        <i class="fas fa-check-circle me-2"></i> Diterima
                    </div>
                    @elseif($pendaftaran->status == 'ditolak')
                    <div class="badge bg-danger w-100 py-3 fs-6 d-flex align-items-center justify-content-center">
                        <i class="fas fa-times-circle me-2"></i> Ditolak
                    </div>
                    @elseif($pendaftaran->status == 'cadangan')
                    <div class="badge bg-info w-100 py-3 fs-6 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user-clock me-2"></i> Cadangan
                    </div>
                    @endif
                </div>
            </div>
            
            @if($nextStep)
            <div class="alert alert-info d-flex mb-4 p-3" role="alert">
                <i class="fas fa-info-circle me-3 fa-lg"></i>
                <div>
                    {{ $nextStep }}
                </div>
            </div>
            @endif
            
            <!-- Personal Information -->
            <div class="card mb-4 border-0 bg-light">
                <div class="card-header bg-secondary bg-opacity-10 py-3">
                    <h5 class="mb-0 fs-5">
                        <i class="fas fa-user me-2"></i> Data Pribadi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold text-secondary mb-1 small">Nama Lengkap:</label>
                            <p class="mb-0">{{ $pendaftaran->nama_lengkap }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-secondary mb-1 small">NISN:</label>
                            <p class="mb-0">{{ $pendaftaran->nisn }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-secondary mb-1 small">Jenis Kelamin:</label>
                            <p class="mb-0">{{ $pendaftaran->jenis_kelamin }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-secondary mb-1 small">Telepon:</label>
                            <p class="mb-0">{{ $pendaftaran->telepon }}</p>
                        </div>
                        <div class="col-12">
                            <label class="fw-bold text-secondary mb-1 small">Alamat:</label>
                            <p class="mb-0">{{ $pendaftaran->alamat }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- School Information -->
            <div class="card mb-4 border-0 bg-light">
                <div class="card-header bg-secondary bg-opacity-10 py-3">
                    <h5 class="mb-0 fs-5">
                        <i class="fas fa-school me-2"></i> Data Sekolah
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="fw-bold text-secondary mb-1 small">Asal Sekolah:</label>
                            <p class="mb-0">{{ $pendaftaran->asal_sekolah }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-secondary mb-1 small">Jurusan Pilihan:</label>
                            <p class="mb-0">{{ $pendaftaran->jurusanPertama->nama_jurusan ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <h6 class="mb-3 fw-bold text-primary">Nilai Ujian</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card h-100 bg-white border-0 shadow-sm">
                                <div class="card-body text-center p-3">
                                    <div class="small text-secondary mb-1">Matematika</div>
                                    <div class="fs-3 fw-bold text-primary">{{ $pendaftaran->nilai_matematika ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 bg-white border-0 shadow-sm">
                                <div class="card-body text-center p-3">
                                    <div class="small text-secondary mb-1">B. Indonesia</div>
                                    <div class="fs-3 fw-bold text-primary">{{ $pendaftaran->nilai_indonesia ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 bg-white border-0 shadow-sm">
                                <div class="card-body text-center p-3">
                                    <div class="small text-secondary mb-1">B. Inggris</div>
                                    <div class="fs-3 fw-bold text-primary">{{ $pendaftaran->nilai_inggris ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex flex-column flex-md-row gap-3 mt-4">
        <a href="{{ route('pendaftaran.edit') }}" 
           class="btn btn-secondary-ppdb flex-grow-1">
            <i class="fas fa-edit me-2"></i>Edit Data
        </a>
        <a href="{{ route('pendaftaran.print', ['nomor' => $pendaftaran->nomor_pendaftaran, 'nisn' => $pendaftaran->nisn]) }}" 
           target="_blank"
           class="btn btn-ppdb flex-grow-1">
            <i class="fas fa-print me-2"></i>Cetak Bukti
        </a>
    </div>
</div>
@endsection
