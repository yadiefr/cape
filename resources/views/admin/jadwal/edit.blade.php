@extends('layouts.admin')

@section('title', 'Edit Jadwal Pelajaran')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit fa-fw"></i> Edit Jadwal Pelajaran
        </h1>
        <div>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit"></i> Form Edit Jadwal
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.jadwal.update', $jadwal->id) }}" id="jadwalForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kelas_id">Kelas <span class="text-danger">*</span></label>
                                    <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($kelasList as $kelas)
                                            <option value="{{ $kelas->id }}" {{ old('kelas_id', $jadwal->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                                {{ $kelas->nama_kelas }} - {{ $kelas->jurusan->nama_jurusan ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mata_pelajaran_id">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-control @error('mata_pelajaran_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @foreach($mataPelajaranList as $mapel)
                                            <option value="{{ $mapel->id }}" {{ old('mata_pelajaran_id', $jadwal->mata_pelajaran_id) == $mapel->id ? 'selected' : '' }}>
                                                {{ $mapel->nama_pelajaran }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mata_pelajaran_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="guru_id">Guru <span class="text-danger">*</span></label>
                                    <select name="guru_id" id="guru_id" class="form-control @error('guru_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach($guruList as $guru)
                                            <option value="{{ $guru->id }}" {{ old('guru_id', $jadwal->guru_id) == $guru->id ? 'selected' : '' }}>
                                                {{ $guru->nama }} @if($guru->nip) - {{ $guru->nip }} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('guru_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hari">Hari <span class="text-danger">*</span></label>
                                    <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror" required>
                                        <option value="">-- Pilih Hari --</option>
                                        @foreach(\App\Models\JadwalPelajaran::getDaftarHari() as $key => $value)
                                            <option value="{{ $key }}" {{ old('hari', $jadwal->hari) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('hari')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jam_ke">Jam Ke <span class="text-danger">*</span></label>
                                    <select name="jam_ke" id="jam_ke" class="form-control @error('jam_ke') is-invalid @enderror" required>
                                        <option value="">-- Pilih Jam --</option>
                                        @foreach($jamOptions as $key => $value)
                                            <option value="{{ $key }}" {{ old('jam_ke', $jadwal->jam_ke) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jam_ke')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jam_mulai">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" name="jam_mulai" id="jam_mulai" 
                                           class="form-control @error('jam_mulai') is-invalid @enderror" 
                                           value="{{ old('jam_mulai', $jadwal->jam_mulai ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') : '') }}" required>
                                    @error('jam_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jam_selesai">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" name="jam_selesai" id="jam_selesai" 
                                           class="form-control @error('jam_selesai') is-invalid @enderror" 
                                           value="{{ old('jam_selesai', $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '') }}" required>
                                    @error('jam_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ruang_kelas">Ruang Kelas</label>
                                    <input type="text" name="ruang_kelas" id="ruang_kelas" 
                                           class="form-control @error('ruang_kelas') is-invalid @enderror" 
                                           value="{{ old('ruang_kelas', $jadwal->ruang_kelas) }}" 
                                           placeholder="Contoh: Ruang 101, Lab Komputer, dll">
                                    @error('ruang_kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">Status</label>
                                    <select name="is_active" id="is_active" class="form-control @error('is_active') is-invalid @enderror">
                                        <option value="1" {{ old('is_active', $jadwal->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('is_active', $jadwal->is_active) == '0' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" 
                                      class="form-control @error('keterangan') is-invalid @enderror" 
                                      rows="3" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $jadwal->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Jadwal
                            </button>
                            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <a href="{{ route('admin.jadwal.show', $jadwal->id) }}" class="btn btn-info ml-2">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-md-4">
            <!-- Current Jadwal Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle"></i> Info Jadwal
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="40%"><strong>Kelas:</strong></td>
                            <td>{{ $jadwal->kelas->nama_kelas }} - {{ $jadwal->kelas->jurusan->nama_jurusan ?? '' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Mata Pelajaran:</strong></td>
                            <td>{{ $jadwal->mataPelajaran->nama_pelajaran }}</td>
                        </tr>
                        <tr>
                            <td><strong>Guru:</strong></td>
                            <td>{{ $jadwal->guru->nama }}</td>
                        </tr>
                        <tr>
                            <td><strong>Hari:</strong></td>
                            <td>{{ $jadwal->hari_formatted }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jam Ke:</strong></td>
                            <td>{{ $jadwal->jam_ke }}</td>
                        </tr>
                        <tr>
                            <td><strong>Waktu:</strong></td>
                            <td>{{ $jadwal->jam_format }}</td>
                        </tr>
                        <tr>
                            <td><strong>Ruang:</strong></td>
                            <td>{{ $jadwal->ruang_kelas ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if($jadwal->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    <small class="text-muted">
                        <i class="fas fa-clock"></i> Dibuat: {{ $jadwal->created_at->format('d/m/Y H:i') }}<br>
                        <i class="fas fa-edit"></i> Diubah: {{ $jadwal->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>

            <!-- Settings Jadwal Info -->
            @if($settingsJadwal->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-cog"></i> Referensi Pengaturan
                        </h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">Pengaturan jadwal yang tersedia:</small>
                        @foreach($settingsJadwal as $setting)
                            <div class="border-left-primary pl-3 py-2 mb-2">
                                <div class="font-weight-bold text-primary">{{ $setting->hari_formatted }}</div>
                                <div class="text-sm">
                                    Waktu: {{ $setting->jam_format }}<br>
                                    Jam Pelajaran: {{ $setting->jumlah_jam_pelajaran }}<br>
                                    Durasi: {{ $setting->durasi_per_jam }} menit
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-2">
                            <a href="{{ route('admin.settings.jadwal.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-edit"></i> Kelola Pengaturan
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tips -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Perhatian
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-exclamation-circle text-warning"></i>
                            <small>Perubahan jadwal akan mempengaruhi sistem secara keseluruhan</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-exclamation-circle text-warning"></i>
                            <small>Pastikan tidak ada jadwal yang bentrok setelah perubahan</small>
                        </li>
                        <li>
                            <i class="fas fa-exclamation-circle text-warning"></i>
                            <small>Koordinasikan dengan guru dan kelas terkait</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-fill jam based on settings when hari and jam_ke selected
    $('#hari, #jam_ke').on('change', function() {
        var hari = $('#hari').val();
        var jamKe = $('#jam_ke').val();
        
        if (hari && jamKe) {
            calculateJamOtomatis(hari, jamKe);
        }
    });

    // Form validation
    $('#jadwalForm').on('submit', function(e) {
        var jamMulai = $('#jam_mulai').val();
        var jamSelesai = $('#jam_selesai').val();
        
        if (jamMulai && jamSelesai && jamMulai >= jamSelesai) {
            e.preventDefault();
            alert('Jam selesai harus lebih besar dari jam mulai!');
            return false;
        }
    });

    // Check for conflicts when key fields change
    $('#kelas_id, #guru_id, #hari, #jam_ke').on('change', function() {
        checkJadwalConflict();
    });
});

function calculateJamOtomatis(hari, jamKe) {
    // Implementation would depend on settings data structure
    // For now, just set default durations
    var durasiPerJam = 45; // minutes
    var jamMulaiSekolah = '07:00';
    
    // Only auto-fill if current values are empty
    if (!$('#jam_mulai').val() || !$('#jam_selesai').val()) {
        // Calculate based on jam_ke
        var mulaiMinutes = parseInt(jamMulaiSekolah.split(':')[0]) * 60 + parseInt(jamMulaiSekolah.split(':')[1]);
        mulaiMinutes += (jamKe - 1) * durasiPerJam;
        
        var jamMulai = Math.floor(mulaiMinutes / 60) + ':' + String(mulaiMinutes % 60).padStart(2, '0');
        var jamSelesai = Math.floor((mulaiMinutes + durasiPerJam) / 60) + ':' + String((mulaiMinutes + durasiPerJam) % 60).padStart(2, '0');
        
        if (!$('#jam_mulai').val()) $('#jam_mulai').val(jamMulai);
        if (!$('#jam_selesai').val()) $('#jam_selesai').val(jamSelesai);
    }
}

function checkJadwalConflict() {
    var kelasId = $('#kelas_id').val();
    var guruId = $('#guru_id').val();
    var hari = $('#hari').val();
    var jamKe = $('#jam_ke').val();
    var currentId = {{ $jadwal->id }};
    
    if (kelasId && guruId && hari && jamKe) {
        // Show loading indicator
        $('#conflict-status').html('<i class="fas fa-spinner fa-spin"></i> Mengecek konflik...');
        
        // AJAX call to check for conflicts
        $.ajax({
            url: '{{ route("admin.jadwal.check-conflict") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                kelas_id: kelasId,
                guru_id: guruId,
                hari: hari,
                jam_ke: jamKe,
                exclude_id: currentId
            },
            success: function(response) {
                if (response.conflict) {
                    $('#conflict-status').html('<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Peringatan: Ada konflik jadwal!</div>');
                } else {
                    $('#conflict-status').html('<div class="alert alert-success"><i class="fas fa-check"></i> Tidak ada konflik jadwal.</div>');
                }
            },
            error: function() {
                $('#conflict-status').html('');
            }
        });
    }
}
</script>
@endpush

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
</style>
@endpush
