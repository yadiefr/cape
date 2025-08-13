@extends('layouts.app-ppdb')

@section('title', 'Pendaftaran Ditutup - SMK PGRI CIKAMPEK')

@section('content')
<div class="ppdb-card">
    <!-- Header -->
    <div class="ppdb-section-header">
        <h2 class="text-center mb-0">Pendaftaran PPDB Ditutup</h2>
    </div>

    <div class="mt-4 text-center">
        <div class="ppdb-title">Pendaftaran Belum Dibuka</div>
        <p class="ppdb-subtitle">
            Tahun Ajaran {{ $ppdb_year }}
        </p>
    </div>

    <div class="alert alert-danger d-flex mb-4" role="alert">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
            </div>
            <div>
                <h4 class="alert-heading fs-5 fw-bold">Pendaftaran Ditutup!</h4>
                <p class="mb-0">
                    @if(isset($message))
                        {{ $message }}
                    @else
                        Pendaftaran Peserta Didik Baru saat ini sedang ditutup. Kami sarankan untuk memeriksa kembali website kami secara berkala untuk mendapatkan informasi terbaru mengenai periode pendaftaran berikutnya, atau hubungi kami melalui nomor kontak yang tersedia.
                    @endif
                </p>
            </div>
        </div>
    </div>
    
    @if($ppdb_start_date && $ppdb_end_date)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary bg-gradient text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-alt fa-lg me-2"></i>
                    <h5 class="mb-0">Periode Pendaftaran PPDB</h5>
                </div>
            </div>
            
            <div class="card-body p-4 text-center">
                <span class="badge bg-primary px-4 py-3 rounded-pill fs-6 d-inline-flex align-items-center">
                    <i class="fas fa-calendar me-2"></i>
                    {{ \Carbon\Carbon::parse($ppdb_start_date)->format('d F Y') }} - {{ \Carbon\Carbon::parse($ppdb_end_date)->format('d F Y') }}
                </span>
            </div>
        </div>
    @endif
    
    <div class="help-section mt-4">
        <h3 class="mb-3">Sudah mendaftar sebelumnya?</h3>
        <p class="mb-3">
            Jika Anda sudah mendaftar sebelumnya, Anda masih dapat memeriksa status pendaftaran Anda.
        </p>
        
        <div class="d-flex flex-column flex-md-row gap-3">
            <a href="{{ route('pendaftaran.check') }}" class="btn btn-ppdb flex-grow-1">
                <i class="fas fa-search me-2"></i> Cek Status Pendaftaran
            </a>
            <a href="{{ url('/') }}" class="btn btn-secondary-ppdb flex-grow-1">
                <i class="fas fa-home me-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
    
    <div class="mt-4 text-center">
        <div class="help-item d-inline-block text-center">
            <div class="help-icon mx-auto mb-2">
                <i class="fas fa-phone"></i>
            </div>
            <div class="help-text">
                <p>Untuk informasi lebih lanjut, silakan hubungi:</p>
                <strong>0812-3456-7890</strong>
            </div>
        </div>
    </div>
</div>
@endsection
