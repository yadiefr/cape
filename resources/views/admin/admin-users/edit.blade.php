@extends('layouts.admin')

@section('title', 'Edit Admin User')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-edit me-2 text-primary"></i>
                Edit Admin User
            </h1>
            <p class="text-muted mb-0">Edit informasi {{ $adminUser->name }}</p>
        </div>
        <a href="{{ route('admin.admin-users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit me-2"></i>Informasi Admin User
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.admin-users.update', $adminUser) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $adminUser->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $adminUser->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="">Pilih Role</option>
                                        @foreach($roles as $key => $value)
                                            <option value="{{ $key }}" {{ old('role', $adminUser->role) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="aktif" {{ old('status', $adminUser->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ old('status', $adminUser->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">NIP</label>
                                    <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                                           value="{{ old('nip', $adminUser->nip) }}">
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $adminUser->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" 
                                           value="{{ old('birth_date', $adminUser->birth_date?->format('Y-m-d')) }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('gender', $adminUser->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('gender', $adminUser->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                              rows="3">{{ old('address', $adminUser->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Foto Profil</label>
                                    @if($adminUser->photo)
                                        <div class="mb-2">
                                            <img src="{{ $adminUser->photo_url }}" alt="Current Photo" class="rounded" width="100">
                                        </div>
                                    @endif
                                    <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" 
                                           accept="image/*">
                                    <small class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</small>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.admin-users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informasi User
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ $adminUser->photo_url }}" alt="{{ $adminUser->name }}" 
                             class="rounded-circle mb-2" width="80" height="80">
                        <h6 class="mb-0">{{ $adminUser->name }}</h6>
                        <small class="text-muted">{{ $adminUser->role_display }}</small>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Dibuat:</strong></p>
                            <p class="text-muted">{{ $adminUser->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Diupdate:</strong></p>
                            <p class="text-muted">{{ $adminUser->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Last Login:</strong></p>
                            <p class="text-muted">
                                @if($adminUser->last_login_at)
                                    {{ $adminUser->last_login_at->format('d/m/Y H:i') }}
                                @else
                                    Belum login
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Status:</strong></p>
                            <span class="badge bg-{{ $adminUser->status == 'aktif' ? 'success' : 'danger' }}">
                                {{ ucfirst($adminUser->status) }}
                            </span>
                        </div>
                    </div>
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
                        <form action="{{ route('admin.admin-users.reset-password', $adminUser) }}" method="POST" 
                              onsubmit="return confirm('Reset password ke default?')">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm w-100">
                                <i class="fas fa-key me-2"></i>Reset Password
                            </button>
                        </form>
                        
                        @if($adminUser->id !== auth()->id())
                        <button type="button" class="btn btn-info btn-sm" onclick="toggleStatus({{ $adminUser->id }})">
                            <i class="fas fa-toggle-{{ $adminUser->status == 'aktif' ? 'on' : 'off' }} me-2"></i>
                            {{ $adminUser->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                        @endif
                    </div>
                </div>
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
</script>
@endpush
@endsection
