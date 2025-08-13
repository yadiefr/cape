@extends('layouts.ujian')

@section('title', 'Detail Pengawas - ' . $jadwal->mataPelajaran->nama)

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <a href="{{ route('admin.ujian.pengawas.index') }}" 
                           class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Pengawas Ujian</h1>
                    </div>
                    <p class="text-sm text-gray-600">{{ $jadwal->mataPelajaran->nama }} - {{ $jadwal->kelas->nama }}</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="assignModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Tambah Pengawas</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Jadwal Info -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Jadwal</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tanggal & Waktu</label>
                    <div class="mt-1">
                        <p class="text-lg font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d/m/Y') }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                        </p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Mata Pelajaran</label>
                    <div class="mt-1">
                        <p class="text-lg font-medium text-gray-900">{{ $jadwal->mataPelajaran->nama }}</p>
                        <p class="text-sm text-gray-600">{{ $jadwal->jenisUjian->nama ?? ucfirst($jadwal->jenis_ujian) }}</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Kelas & Ruangan</label>
                    <div class="mt-1">
                        <p class="text-lg font-medium text-gray-900">{{ $jadwal->kelas->nama }}</p>
                        <p class="text-sm text-gray-600">{{ $jadwal->ruangan->nama }}</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Status Pengawas</label>
                    <div class="mt-1">
                        @if($jadwal->pengawas->count() >= 2)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Pengawas Lengkap
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Perlu {{ 2 - $jadwal->pengawas->count() }} Pengawas
                            </span>
                        @endif
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $jadwal->pengawas->count() }} dari 2 pengawas minimal
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengawas List -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Pengawas</h3>
                    <span class="text-sm text-gray-500">{{ $jadwal->pengawas->count() }} pengawas</span>
                </div>
            </div>
            
            @if($jadwal->pengawas->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pengawas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($jadwal->pengawas as $pengawas)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ substr($pengawas->guru->nama, 0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $pengawas->guru->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $pengawas->guru->mata_pelajaran }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $pengawas->jenis_pengawas == 'utama' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    @if($pengawas->jenis_pengawas == 'utama')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Pengawas Utama
                                    @else
                                        Pengawas Pendamping
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select onchange="updateKehadiran({{ $pengawas->id }}, this.value)" 
                                        class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                                    <option value="belum_dicek" {{ !$pengawas->is_hadir && !$pengawas->keterangan_tidak_hadir ? 'selected' : '' }}>Belum Dicek</option>
                                    <option value="hadir" {{ $pengawas->is_hadir ? 'selected' : '' }}>Hadir</option>
                                    <option value="tidak_hadir" {{ !$pengawas->is_hadir && $pengawas->keterangan_tidak_hadir == 'Tidak hadir tanpa keterangan' ? 'selected' : '' }}>Tidak Hadir</option>
                                    <option value="sakit" {{ !$pengawas->is_hadir && $pengawas->keterangan_tidak_hadir == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="izin" {{ !$pengawas->is_hadir && $pengawas->keterangan_tidak_hadir == 'Izin' ? 'selected' : '' }}>Izin</option>
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs">
                                    {{ $pengawas->catatan ?: '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="editCatatanModal({{ $pengawas->id }}, '{{ addslashes($pengawas->catatan) }}')" 
                                            class="text-blue-600 hover:text-blue-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="removePengawas({{ $pengawas->id }})" 
                                            class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-12 text-center">
                <div class="text-gray-400">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pengawas</h3>
                    <p class="text-gray-500 mb-4">Jadwal ujian ini belum memiliki pengawas yang ditugaskan.</p>
                    <button onclick="assignModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                        Tambah Pengawas Pertama
                    </button>
                </div>
            </div>
            @endif
        </div>

        <!-- Guru Tersedia -->
        <div class="bg-white rounded-lg shadow-sm border mt-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Guru Tersedia</h3>
                <p class="text-sm text-gray-600 mt-1">Guru yang tidak memiliki jadwal bertabrakan pada waktu ini</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($guruTersedia as $guru)
                    <div class="border rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $guru->nama }}</h4>
                                <p class="text-sm text-gray-600">{{ $guru->mata_pelajaran }}</p>
                            </div>
                            <button onclick="quickAssign({{ $guru->id }})" 
                                    class="text-green-600 hover:text-green-800 text-sm font-medium">
                                Assign
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        Tidak ada guru yang tersedia pada waktu ini
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Modal -->
<div id="assignModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tambah Pengawas</h3>
                <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="assignForm" action="{{ route('admin.ujian.pengawas.assign', $jadwal->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Guru</label>
                    <select name="guru_id" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Guru</option>
                        @foreach($allGuru as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }} - {{ $g->mata_pelajaran }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengawas</label>
                    <select name="jenis_pengawas" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="utama">Pengawas Utama</option>
                        <option value="pendamping">Pengawas Pendamping</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="3" placeholder="Catatan tambahan..." 
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAssignModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                        Tambah Pengawas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Catatan Modal -->
<div id="editCatatanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Catatan</h3>
                <button onclick="closeEditCatatanModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="editCatatanForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="catatan" id="editCatatanTextarea" rows="4" 
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditCatatanModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function assignModal() {
    document.getElementById('assignModal').classList.remove('hidden');
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
}

function editCatatanModal(pengawasId, catatan) {
    const form = document.getElementById('editCatatanForm');
    const textarea = document.getElementById('editCatatanTextarea');
    
    form.action = `/admin/ujian/pengawas/update-catatan/${pengawasId}`;
    textarea.value = catatan;
    
    document.getElementById('editCatatanModal').classList.remove('hidden');
}

function closeEditCatatanModal() {
    document.getElementById('editCatatanModal').classList.add('hidden');
}

function quickAssign(guruId) {
    const form = document.getElementById('assignForm');
    const guruSelect = form.querySelector('select[name="guru_id"]');
    
    guruSelect.value = guruId;
    assignModal();
}

function updateKehadiran(pengawasId, kehadiran) {
    fetch(`/admin/ujian/pengawas/update-kehadiran/${pengawasId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ kehadiran: kehadiran })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Optional: show success message
            console.log('Kehadiran berhasil diupdate');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate kehadiran');
    });
}

function removePengawas(pengawasId) {
    if (confirm('Apakah Anda yakin ingin menghapus pengawas ini?')) {
        fetch(`/admin/ujian/pengawas/remove/${pengawasId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan saat menghapus pengawas');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus pengawas');
        });
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const assignModal = document.getElementById('assignModal');
    const editCatatanModal = document.getElementById('editCatatanModal');
    
    if (event.target === assignModal) {
        closeAssignModal();
    }
    if (event.target === editCatatanModal) {
        closeEditCatatanModal();
    }
}
</script>
@endsection
