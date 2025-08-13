@extends('layouts.guru')

@section('title', 'Detail Nilai - SMK PGRI CIKAMPEK')

@push('styles')
<style>
    .detail-header {
        opacity: 0;
        transform: translateY(-20px);
        animation: slideInFromTop 0.6s ease-out forwards;
    }
    
    .siswa-card {
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.4s ease-out;
    }
    
    @keyframes slideInFromTop {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive control */
    @media (min-width: 768px) {
        #desktop-view {
            display: block !important;
        }
        #mobile-view {
            display: none !important;
        }
    }

    @media (max-width: 767px) {
        #desktop-view {
            display: none !important;
        }
        #mobile-view {
            display: block !important;
        }
    }
</style>
@endpush

@section('main-content')
<div class="px-3 py-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 detail-header gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
                Detail Nilai {{ $selectedMapel->nama }}
            </h1>
            <p class="text-sm sm:text-base text-gray-600">
                Kelas {{ $kelas->nama_kelas }} - {{ $kelas->jurusan->nama_jurusan ?? $kelas->jurusan->nama ?? 'Belum ada jurusan' }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 text-center sm:text-left">
            <a href="{{ route('guru.nilai.show', $kelas->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $nilaiDetail['total_siswa'] }}</div>
            <div class="text-xs text-gray-500">Total Siswa</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $nilaiDetail['sudah_dinilai'] }}</div>
            <div class="text-xs text-gray-500">Sudah Dinilai</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
            <div class="text-2xl font-bold text-red-600">{{ $nilaiDetail['belum_dinilai'] }}</div>
            <div class="text-xs text-gray-500">Belum Dinilai</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
            <a href="{{ route('guru.nilai.create', ['kelas_id' => $kelas->id, 'mapel_id' => $selectedMapel->id]) }}" 
               class="block bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded-lg transition-colors text-xs">
                <i class="fas fa-plus mr-1"></i> Input Nilai
            </a>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div id="desktop-view">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Siswa & Nilai</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daftar Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($nilaiDetail['siswa_data'] as $index => $data)
                        <tr class="hover:bg-gray-50 siswa-card">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $data['siswa']->nama_lengkap ?? ($data['siswa']->nama ?? 'Nama tidak tersedia') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data['siswa']->nis ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($data['has_nilai'])
                                    <div class="space-y-2">
                                        @php
                                            $displayedGroups = [];
                                        @endphp
                                        @foreach($data['nilai_records'] as $nilai)
                                        @php
                                            $groupKey = $nilai->jenis_nilai . '|' . $nilai->created_at->format('Y-m-d H:i:s');
                                            $isFirstInGroup = !in_array($groupKey, $displayedGroups);
                                            if ($isFirstInGroup) {
                                                $displayedGroups[] = $groupKey;
                                            }
                                        @endphp
                                        <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2">
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            @php
                                                                $jenisNilaiLabels = [
                                                                    'tugas' => 'Tugas Harian',
                                                                    'ulangan' => 'Ulangan Harian',
                                                                    'uts' => 'UTS',
                                                                    'uas' => 'UAS',
                                                                    'praktik' => 'Praktik'
                                                                ];
                                                            @endphp
                                                            {{ $jenisNilaiLabels[$nilai->jenis_nilai] ?? ucfirst($nilai->jenis_nilai) }}
                                                        </span>
                                                        @if($nilai->deskripsi)
                                                        <div class="text-xs text-gray-600 mt-1 font-medium">{{ $nilai->deskripsi }}</div>
                                                        @endif
                                                        @if($nilai->catatan)
                                                        <div class="text-xs text-gray-500 mt-1">{{ $nilai->catatan }}</div>
                                                        @endif
                                                    </div>
                                                    @if($isFirstInGroup)
                                                    <div class="flex items-center space-x-1 ml-2">
                                                        <a href="{{ route('guru.nilai.edit-batch', ['kelas_id' => $kelas->id, 'mapel_id' => $selectedMapel->id, 'jenis_nilai' => $nilai->jenis_nilai, 'created_at' => $nilai->created_at->format('Y-m-d H:i:s')]) }}"
                                                           class="text-blue-600 hover:text-blue-800 text-xs p-1" title="Edit nilai {{ $jenisNilaiLabels[$nilai->jenis_nilai] ?? $nilai->jenis_nilai }} ({{ $nilai->created_at->format('d/m/Y H:i') }})">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button onclick="confirmDeleteBatch('{{ $nilai->jenis_nilai }}', '{{ $kelas->id }}', '{{ $selectedMapel->id }}', '{{ $jenisNilaiLabels[$nilai->jenis_nilai] ?? $nilai->jenis_nilai }}', '{{ $nilai->created_at->format('Y-m-d H:i:s') }}')"
                                                                class="text-red-600 hover:text-red-800 text-xs p-1" title="Hapus nilai {{ $jenisNilaiLabels[$nilai->jenis_nilai] ?? $nilai->jenis_nilai }} ({{ $nilai->created_at->format('d/m/Y H:i') }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <span class="text-lg font-bold text-blue-600">{{ $nilai->nilai }}</span>
                                                <span class="text-xs text-gray-400">{{ $nilai->created_at->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <span class="px-3 py-2 bg-red-100 text-red-800 text-xs rounded-full font-semibold">
                                            <i class="fas fa-times mr-1"></i>Belum ada nilai
                                        </span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div id="mobile-view" class="hidden">
        @foreach($nilaiDetail['siswa_data'] as $index => $data)
        <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm siswa-card mb-3">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center flex-1 min-w-0 mr-2">
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full mr-2 flex-shrink-0">
                        {{ $index + 1 }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-sm font-semibold text-gray-900 truncate">
                            {{ $data['siswa']->nama_lengkap ?? ($data['siswa']->nama ?? 'Nama tidak tersedia') }}
                        </h3>
                        <p class="text-xs text-gray-500 truncate">{{ $data['siswa']->nis ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    @if($data['has_nilai'])
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-semibold">
                            {{ $data['nilai_records']->count() }}
                        </span>
                    @else
                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-semibold">
                            0
                        </span>
                    @endif
                </div>
            </div>
            
            @if($data['has_nilai'])
            <div class="border-t pt-3">
                @php
                    $mobileDisplayedGroups = [];
                @endphp
                @foreach($data['nilai_records'] as $nilai)
                @php
                    $mobileGroupKey = $nilai->jenis_nilai . '|' . $nilai->created_at->format('Y-m-d H:i:s');
                    $isMobileFirstInGroup = !in_array($mobileGroupKey, $mobileDisplayedGroups);
                    if ($isMobileFirstInGroup) {
                        $mobileDisplayedGroups[] = $mobileGroupKey;
                    }
                @endphp
                <div class="bg-gray-50 rounded-lg p-2 mb-2">
                    <div class="flex justify-between items-center">
                        <div class="flex-1 min-w-0 mr-2">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-medium text-gray-700">
                                    @php
                                        $jenisNilaiLabels = [
                                            'tugas' => 'Tugas Harian',
                                            'ulangan' => 'Ulangan Harian',
                                            'uts' => 'UTS',
                                            'uas' => 'UAS',
                                            'praktik' => 'Praktik'
                                        ];
                                    @endphp
                                    {{ $jenisNilaiLabels[$nilai->jenis_nilai] ?? ucfirst($nilai->jenis_nilai) }}
                                </span>
                                @if($isMobileFirstInGroup)
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('guru.nilai.edit-batch', ['kelas_id' => $kelas->id, 'mapel_id' => $selectedMapel->id, 'jenis_nilai' => $nilai->jenis_nilai, 'created_at' => $nilai->created_at->format('Y-m-d H:i:s')]) }}"
                                       class="text-blue-600 hover:text-blue-800 text-xs p-1" title="Edit nilai {{ $jenisNilaiLabels[$nilai->jenis_nilai] ?? $nilai->jenis_nilai }} ({{ $nilai->created_at->format('d/m/Y H:i') }})">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDeleteBatch('{{ $nilai->jenis_nilai }}', '{{ $kelas->id }}', '{{ $selectedMapel->id }}', '{{ $jenisNilaiLabels[$nilai->jenis_nilai] ?? $nilai->jenis_nilai }}', '{{ $nilai->created_at->format('Y-m-d H:i:s') }}')"
                                            class="text-red-600 hover:text-red-800 text-xs p-1" title="Hapus nilai {{ $jenisNilaiLabels[$nilai->jenis_nilai] ?? $nilai->jenis_nilai }} ({{ $nilai->created_at->format('d/m/Y H:i') }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                            @if($nilai->deskripsi)
                            <div class="text-xs text-gray-600 mt-1 font-medium">{{ $nilai->deskripsi }}</div>
                            @endif
                            @if($nilai->catatan)
                            <div class="text-xs text-gray-500 mt-1">{{ $nilai->catatan }}</div>
                            @endif
                        </div>
                        <div class="flex flex-col items-end flex-shrink-0">
                            <span class="text-lg font-bold text-blue-600">{{ $nilai->nilai }}</span>
                            <span class="text-xs text-gray-400">{{ $nilai->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="border-t pt-3 text-center">
                <p class="text-xs text-gray-500">Belum ada nilai</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function handleResponsiveView() {
        const desktopView = document.getElementById('desktop-view');
        const mobileView = document.getElementById('mobile-view');
        
        if (window.innerWidth >= 768) {
            if (desktopView) desktopView.style.display = 'block';
            if (mobileView) mobileView.style.display = 'none';
        } else {
            if (desktopView) desktopView.style.display = 'none';
            if (mobileView) mobileView.style.display = 'block';
        }
    }
    
    handleResponsiveView();
    window.addEventListener('resize', handleResponsiveView);
    
    const siswaCards = document.querySelectorAll('.siswa-card');
    siswaCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateX(0)';
        }, 300 + (index * 50));
    });
});

// Function untuk konfirmasi hapus batch
function confirmDeleteBatch(jenisNilai, kelasId, mapelId, jenisNilaiLabel, createdAt) {
    const tanggalText = new Date(createdAt).toLocaleString('id-ID');
    if (confirm(`Yakin ingin menghapus nilai ${jenisNilaiLabel} (${tanggalText}) untuk seluruh siswa di kelas ini?`)) {
        // Create form untuk delete
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("guru.nilai.delete-batch") }}';

        // CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Method DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);

        // Parameters
        const kelasInput = document.createElement('input');
        kelasInput.type = 'hidden';
        kelasInput.name = 'kelas_id';
        kelasInput.value = kelasId;
        form.appendChild(kelasInput);

        const mapelInput = document.createElement('input');
        mapelInput.type = 'hidden';
        mapelInput.name = 'mapel_id';
        mapelInput.value = mapelId;
        form.appendChild(mapelInput);

        const jenisInput = document.createElement('input');
        jenisInput.type = 'hidden';
        jenisInput.name = 'jenis_nilai';
        jenisInput.value = jenisNilai;
        form.appendChild(jenisInput);

        const createdAtInput = document.createElement('input');
        createdAtInput.type = 'hidden';
        createdAtInput.name = 'created_at';
        createdAtInput.value = createdAt || '';
        form.appendChild(createdAtInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
