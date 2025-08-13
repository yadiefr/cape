@extends('layouts.admin')

@section('title', 'Detail Admin User')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user me-2 text-primary"></i>
                Detail Admin User
            </h1>
            <p class="text-muted mb-0">Informasi lengkap {{ $adminUser->name }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.admin-users.edit', $adminUser) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('admin.admin-users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <img src="{{ $adminUser->photo_url }}" alt="{{ $adminUser->name }}" 
                         class="rounded-circle mb-3" width="120" height="120">
                    <h4 class="mb-1">{{ $adminUser->name }}</h4>
                    <p class="text-muted mb-2">{{ $adminUser->email }}</p>
                    <span class="badge bg-primary fs-6 mb-3">{{ $adminUser->role_display }}</span>
                    <br>
                    <span class="badge bg-{{ $adminUser->status == 'aktif' ? 'success' : 'danger' }} fs-6">
                        {{ ucfirst($adminUser->status) }}
                    </span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.admin-users.edit', $adminUser) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit User
                        </a>
                        
                        <form action="{{ route('admin.admin-users.reset-password', $adminUser) }}" method="POST" 
                              onsubmit="return confirm('Reset password ke default?')">
                            @csrf
                            <button type="submit" class="btn btn-info w-100">
                                <i class="fas fa-key me-2"></i>Reset Password
                            </button>
                        </form>
                        
                        @if($adminUser->id !== auth()->id())
                        <button type="button" class="btn btn-{{ $adminUser->status == 'aktif' ? 'danger' : 'success' }}" 
                                onclick="toggleStatus({{ $adminUser->id }})">
                            <i class="fas fa-toggle-{{ $adminUser->status == 'aktif' ? 'off' : 'on' }} me-2"></i>
                            {{ $adminUser->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                        
                        <button type="button" class="btn btn-danger" onclick="deleteUser({{ $adminUser->id }})">
                            <i class="fas fa-trash me-2"></i>Hapus User
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle me-2"></i>Informasi Personal
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <p class="form-control-plaintext">{{ $adminUser->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="form-control-plaintext">{{ $adminUser->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">NIP</label>
                                <p class="form-control-plaintext">{{ $adminUser->nip ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">No. Telepon</label>
                                <p class="form-control-plaintext">{{ $adminUser->phone ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Lahir</label>
                                <p class="form-control-plaintext">
                                    {{ $adminUser->birth_date ? $adminUser->birth_date->format('d F Y') : '-' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                <p class="form-control-plaintext">
                                    @if($adminUser->gender == 'L')
                                        Laki-laki
                                    @elseif($adminUser->gender == 'P')
                                        Perempuan
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat</label>
                                <p class="form-control-plaintext">{{ $adminUser->address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog me-2"></i>Informasi Sistem
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Role</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-primary fs-6">{{ $adminUser->role_display }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-{{ $adminUser->status == 'aktif' ? 'success' : 'danger' }} fs-6">
                                        {{ ucfirst($adminUser->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Dibuat</label>
                                <p class="form-control-plaintext">{{ $adminUser->created_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Terakhir Diupdate</label>
                                <p class="form-control-plaintext">{{ $adminUser->updated_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Login</label>
                                <p class="form-control-plaintext">
                                    @if($adminUser->last_login_at)
                                        {{ $adminUser->last_login_at->format('d F Y, H:i') }}
                                    @else
                                        <span class="text-muted">Belum pernah login</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Login IP</label>
                                <p class="form-control-plaintext">{{ $adminUser->last_login_ip ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shield-alt me-2"></i>Permissions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $permissions = $adminUser->permissions;
                        @endphp
                        @if(in_array('*', $permissions))
                            <div class="col-12">
                                <span class="badge bg-danger fs-6">FULL ACCESS - Semua Permission</span>
                            </div>
                        @else
                            @foreach($permissions as $permission)
                                <div class="col-md-4 mb-2">
                                    <span class="badge bg-info">{{ $permission }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus admin user <strong>{{ $adminUser->name }}</strong>?
                <br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleStatus(userId) {
    if (confirm('Ubah status user ini?')) {
        fetch(`{{ url('admin/admin-users') }}/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function deleteUser(userId) {
    const form = document.getElementById('deleteForm');
    form.action = `{{ url('admin/admin-users') }}/${userId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endsection
