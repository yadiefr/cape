@extends('layouts.admin')

@section('title', 'Kelola Mata Pelajaran - ' . $guru->nama)

@section('content')
<div id="assign-subjects-app" class="w-full px-3 py-4">
    <!-- Breadcrumb and actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                <a href="{{ route('admin.guru.index') }}" class="hover:text-blue-600">Guru</a>
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                <a href="{{ route('admin.guru.show', $guru) }}" class="hover:text-blue-600">{{ $guru->nama }}</a>
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                <span>Kelola Mata Pelajaran</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-book-open text-blue-500 mr-3"></i> 
                Kelola Mata Pelajaran - {{ $guru->nama }}
            </h1>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.guru.show', $guru) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <i class="fas fa-spinner fa-spin text-blue-500 text-xl"></i>
            <span class="text-gray-700">Memproses...</span>
        </div>
    </div>

    <!-- Messages Container -->
    <div id="messages-container">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 alert-message">
                {{ session('success') }}
                <button type="button" class="float-right text-green-700 hover:text-green-900" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 alert-message">
                {{ session('error') }}
                <button type="button" class="float-right text-red-700 hover:text-red-900" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Add New Subject Assignment -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-plus-circle text-blue-500 mr-2"></i> Tambah Mata Pelajaran
                </h3>
            </div>
            <div class="p-6">
                <form id="assignment-form" action="{{ route('admin.guru.store-subject-assignment', $guru) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($availableSubjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('mapel_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div id="mapel_id_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        @error('mapel_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <div class="mb-2 flex gap-2">
                            <button type="button" id="selectAllClasses" class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 transition-all">
                                Pilih Semua
                            </button>
                            <button type="button" id="clearAllClasses" class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded hover:bg-gray-200 transition-all">
                                Kosongkan
                            </button>
                        </div>
                        <div class="border border-gray-300 rounded-lg p-3 max-h-80 overflow-y-auto">
                            @foreach($availableClasses as $class)
                                <label class="flex items-center mb-2 cursor-pointer hover:bg-gray-50 p-2 rounded transition-all">
                                    <input type="checkbox" name="kelas_id[]" value="{{ $class->id }}" 
                                        {{ in_array($class->id, old('kelas_id', [])) ? 'checked' : '' }}
                                        class="class-checkbox mr-3 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="text-sm text-gray-700">
                                        {{ $class->nama_kelas ?? 'Kelas tidak diketahui' }} - {{ $class->jurusan->nama_jurusan ?? $class->jurusan->nama ?? 'Jurusan tidak diketahui' }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Pilih satu atau beberapa kelas untuk mata pelajaran ini</p>
                        <div id="kelas_id_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        @error('kelas_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('kelas_id.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" id="submit-btn" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-all disabled:opacity-50">
                        <i class="fas fa-plus mr-2" id="submit-icon"></i> 
                        <span id="submit-text">Tambah Mata Pelajaran</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Current Subject Assignments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-list text-blue-500 mr-2"></i> Mata Pelajaran Saat Ini
                </h3>
            </div>
            <div class="p-6">
                <div id="assignments-container">
                    @if($assignedSubjects->count() > 0)
                        <div class="space-y-4" id="assignments-list">
                            @foreach($assignedSubjects as $jadwal)
                                @if($jadwal->mapel && $jadwal->kelas)
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 assignment-item" data-id="{{ $jadwal->id }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="font-medium text-gray-800">
                                                    {{ $jadwal->mapel->nama ?? 'Mata pelajaran tidak diketahui' }}
                                                </h4>
                                                @if($jadwal->hari && $jadwal->jam_ke)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-calendar-check mr-1"></i>
                                                        Terjadwal
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Belum Terjadwal
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">
                                                <i class="fas fa-school mr-1"></i>
                                                {{ $jadwal->kelas->nama_kelas ?? 'Kelas tidak diketahui' }} - {{ $jadwal->kelas->jurusan->nama_jurusan ?? $jadwal->kelas->jurusan->nama ?? 'Jurusan tidak diketahui' }}
                                            </p>
                                            @if($jadwal->hari && $jadwal->jam_ke)
                                                <p class="text-sm text-gray-600 mb-1">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ $jadwal->hari }}, Jam ke-{{ $jadwal->jam_ke }}
                                                    @if($jadwal->jam_mulai && $jadwal->jam_selesai)
                                                        ({{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }})
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                        <button type="button" onclick="removeAssignment({{ $jadwal->id }})" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-all ml-4 delete-btn">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8" id="empty-state">
                            <i class="fas fa-book-open text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">Guru ini belum mengampu mata pelajaran apapun.</p>
                            <p class="text-sm text-gray-400 mt-1">Tambahkan mata pelajaran menggunakan form di sebelah kiri.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const assignmentForm = document.getElementById('assignment-form');
    const loadingOverlay = document.getElementById('loading-overlay');
    const messagesContainer = document.getElementById('messages-container');
    const submitBtn = document.getElementById('submit-btn');
    const submitIcon = document.getElementById('submit-icon');
    const submitText = document.getElementById('submit-text');

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Show loading overlay
    function showLoading() {
        loadingOverlay.classList.remove('hidden');
        submitBtn.disabled = true;
        submitIcon.className = 'fas fa-spinner fa-spin mr-2';
        submitText.textContent = 'Memproses...';
    }

    // Hide loading overlay
    function hideLoading() {
        loadingOverlay.classList.add('hidden');
        submitBtn.disabled = false;
        submitIcon.className = 'fas fa-plus mr-2';
        submitText.textContent = 'Tambah Mata Pelajaran';
    }

    // Show message
    function showMessage(message, type = 'success') {
        const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
        const closeClass = type === 'success' ? 'text-green-700 hover:text-green-900' : 'text-red-700 hover:text-red-900';
        
        const messageHtml = `
            <div class="${alertClass} px-4 py-3 rounded mb-4 alert-message">
                ${message}
                <button type="button" class="float-right ${closeClass}" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        messagesContainer.innerHTML = messageHtml;
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            const alertElement = messagesContainer.querySelector('.alert-message');
            if (alertElement) {
                alertElement.remove();
            }
        }, 5000);
    }

    // Clear error messages
    function clearErrors() {
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }

    // Show validation errors
    function showErrors(errors) {
        clearErrors();
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById(field + '_error');
            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.classList.remove('hidden');
            }
        });
    }

    // AJAX form submission
    assignmentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const checkedBoxes = document.querySelectorAll('.class-checkbox:checked');
        
        if (checkedBoxes.length === 0) {
            showMessage('Pilih minimal satu kelas!', 'error');
            return;
        }

        showLoading();
        clearErrors();

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                showMessage(data.message, 'success');
                
                // Reset form
                assignmentForm.reset();
                document.querySelectorAll('.class-checkbox').forEach(cb => cb.checked = false);
                
                // Reload assignments section
                reloadAssignments();
            } else {
                if (data.errors) {
                    showErrors(data.errors);
                } else {
                    showMessage(data.message || 'Terjadi kesalahan', 'error');
                }
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showMessage('Terjadi kesalahan sistem', 'error');
        });
    });

    // Reload assignments section
    function reloadAssignments() {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newAssignmentsContainer = doc.getElementById('assignments-container');
            
            if (newAssignmentsContainer) {
                document.getElementById('assignments-container').innerHTML = newAssignmentsContainer.innerHTML;
            }
        })
        .catch(error => {
            console.error('Error reloading assignments:', error);
        });
    }

    // Remove assignment function
    window.removeAssignment = function(assignmentId) {
        if (!confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')) {
            return;
        }

        showLoading();

        fetch(`{{ route('admin.guru.remove-subject-assignment', [$guru->id, ':id']) }}`.replace(':id', assignmentId), {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                showMessage(data.message, 'success');
                
                // Remove the assignment item from DOM
                const assignmentItem = document.querySelector(`[data-id="${assignmentId}"]`);
                if (assignmentItem) {
                    assignmentItem.style.opacity = '0';
                    assignmentItem.style.transform = 'translateX(-100%)';
                    setTimeout(() => {
                        assignmentItem.remove();
                        
                        // Check if no assignments left, show empty state
                        const assignmentsList = document.getElementById('assignments-list');
                        if (assignmentsList && assignmentsList.children.length === 0) {
                            document.getElementById('assignments-container').innerHTML = `
                                <div class="text-center py-8" id="empty-state">
                                    <i class="fas fa-book-open text-gray-300 text-4xl mb-4"></i>
                                    <p class="text-gray-500">Guru ini belum mengampu mata pelajaran apapun.</p>
                                    <p class="text-sm text-gray-400 mt-1">Tambahkan mata pelajaran menggunakan form di sebelah kiri.</p>
                                </div>
                            `;
                        }
                    }, 300);
                }
            } else {
                showMessage(data.message || 'Terjadi kesalahan saat menghapus', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showMessage('Terjadi kesalahan sistem', 'error');
        });
    };

    // Select All functionality
    document.getElementById('selectAllClasses').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.class-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    });

    // Clear All functionality
    document.getElementById('clearAllClasses').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.class-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    });

    // Add transition styles for smooth animations
    const style = document.createElement('style');
    style.textContent = `
        .assignment-item {
            transition: all 0.3s ease;
        }
        .alert-message {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
});
</script>
