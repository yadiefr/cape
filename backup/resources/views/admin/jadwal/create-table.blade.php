@extends('layouts.admin')

@section('title', 'Tambah Jadwal Pelajaran')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Jadwal Pelajaran</h1>
                <p class="mt-1 text-sm text-gray-600">Klik pada cell untuk menambah atau mengedit jadwal pelajaran</p>
            </div>
            <nav class="flex mt-4 sm:mt-0" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-300 mr-4"></i>
                        <a href="{{ route('admin.jadwal.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Jadwal</a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-300 mr-4"></i>
                        <span class="text-sm font-medium text-gray-900">Kelola</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="filter_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Kelas
                </label>
                <select id="filter_kelas" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas_list as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filter_semester" class="block text-sm font-medium text-gray-700 mb-2">
                    Semester
                </label>                <select id="filter_semester" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="1">Ganjil</option>
                    <option value="2">Genap</option>
                </select>
            </div>
            <div>
                <label for="filter_tahun" class="block text-sm font-medium text-gray-700 mb-2">
                    Tahun Ajaran
                </label>
                <input type="text" id="filter_tahun" value="{{ $tahun_ajaran_aktif ?? '' }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        <div class="mt-4">
            <button type="button" id="loadSchedule" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150 ease-in-out">
                <i class="fas fa-calendar-alt mr-2"></i>
                Tampilkan Jadwal
            </button>
        </div>
    </div>

    <!-- Tabel Jadwal Per Hari -->
    <div class="space-y-6">
        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                    Jadwal Hari {{ $hari }}
                </h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200 w-20">
                                    Jam Ke                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">
                                    Waktu
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jadwal (Klik untuk edit)
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="scheduleBody{{ $hari }}">
                            <!-- Will be filled by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Loading State -->
    <div id="loadingSchedule" class="hidden bg-white rounded-lg shadow-sm p-12 text-center">
        <div class="inline-flex items-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-3"></div>
            <span class="text-gray-600">Memuat jadwal...</span>
        </div>
    </div>
</div>
@include('admin.jadwal._modal_jadwal')
@push('scripts')
<script>
// --- Helper functions ---
function closeModal() {
    document.getElementById('jadwalModal').classList.add('hidden');
    document.getElementById('jadwalForm').reset();
    document.getElementById('modalError').classList.add('hidden');
    document.getElementById('deleteJadwal').classList.add('hidden');
}

function openModal(hari, jamKe, jamMulai, jamSelesai, jadwalData) {
    // Set hidden fields
    document.getElementById('modal_hari').value = hari;
    document.getElementById('modal_jam_ke').value = jamKe; // Set the jam_ke value that was previously missing
    document.getElementById('modal_jam_mulai').value = jamMulai;
    document.getElementById('modal_jam_selesai').value = jamSelesai;
    document.getElementById('slotInfo').textContent = `${hari}, ${jamMulai} - ${jamSelesai}`;
    
    // Get kelas_id from the filter dropdown
    const kelasId = document.getElementById('filter_kelas').value;
    if (!kelasId) {
        alert('Pilih kelas terlebih dahulu!');
        return false;
    }
    document.getElementById('modal_kelas_id').value = kelasId;
    
    // Ensure semester is always a numeric value (1=Ganjil, 2=Genap)
    let semester = document.getElementById('filter_semester').value;
    document.getElementById('modal_semester').value = semester;
    
    // Get tahun_ajaran from the filter input
    const tahunAjaran = document.getElementById('filter_tahun').value;
    if (!tahunAjaran) {
        alert('Masukkan tahun ajaran terlebih dahulu!');
        return false;
    }
    document.getElementById('modal_tahun_ajaran').value = tahunAjaran;
    
    // For debugging
    console.log('Modal data:', {
        hari,
        jamKe,
        jamMulai,
        jamSelesai,
        kelas: kelasId,
        semester: semester,
        tahunAjaran: tahunAjaran
    });

    if (jadwalData) {
        document.getElementById('modalTitle').textContent = 'Edit Jadwal Pelajaran';
        document.getElementById('jadwal_id').value = jadwalData.id;
        document.getElementById('modal_mapel_id').value = jadwalData.mapel_id;
        document.getElementById('modal_guru_id').value = jadwalData.guru_id;
        document.getElementById('modal_keterangan').value = jadwalData.keterangan || '';
        document.getElementById('modal_is_active').checked = jadwalData.is_active ? true : false;
        document.getElementById('deleteJadwal').classList.remove('hidden');
    } else {
        document.getElementById('modalTitle').textContent = 'Tambah Jadwal Pelajaran';
        document.getElementById('jadwal_id').value = '';
        document.getElementById('modal_mapel_id').value = '';
        document.getElementById('modal_guru_id').value = '';
        document.getElementById('modal_keterangan').value = '';
        document.getElementById('modal_is_active').checked = true;
        document.getElementById('deleteJadwal').classList.add('hidden');
    }
    document.getElementById('jadwalModal').classList.remove('hidden');
}

// --- Main logic ---
document.addEventListener('DOMContentLoaded', function() {
    // Check if settings exist and initialize the app
    fetchJadwalSettings().then(hasSettings => {
        if (!hasSettings) {
            // Create default settings if none exist
            createDefaultSettings().then(success => {
                if (success) {
                    console.log("Created default settings");
                    // Reload settings and generate tables
                    fetchJadwalSettings().then(() => {
                        generateAllScheduleTables();
                    });
                } else {
                    Swal.fire({
                        title: 'Pengaturan Jadwal Belum Ada',
                        text: 'Anda harus mengatur jadwal terlebih dahulu sebelum bisa mengelola jadwal pelajaran.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Atur Jadwal',  
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('admin.settings.jadwal.index') }}";
                        }
                    });
                }
            });
        } else {
            // If settings exist, generate all schedule tables immediately
            // so user doesn't have to press the "Load Schedule" button
            generateAllScheduleTables();
        }
    });

    const loadScheduleBtn = document.getElementById('loadSchedule');
    const filterKelas = document.getElementById('filter_kelas');
    const filterSemester = document.getElementById('filter_semester');
    const filterTahun = document.getElementById('filter_tahun');
    const loadingSchedule = document.getElementById('loadingSchedule');
    let currentSchedules = {};
    let timeSlotSettings = {};    // Fetch jadwal settings (jam, slot, istirahat)
    function fetchJadwalSettings() {
        console.log('Fetching jadwal settings...');
        return fetch('/admin/jadwal/settings')
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok: ' + res.status);
                }
                return res.json();
            })
            .then(data => {
                console.log('Received settings:', data);
                if (data.success && data.settings && data.settings.length > 0) {
                    timeSlotSettings = {};
                    data.settings.forEach(setting => {
                        console.log('Processing setting for day:', setting.hari);
                        
                        // Handle different time formats that might be returned by the server
                        const formatTimeField = (timeField) => {
                            if (!timeField) return null;
                            // If it's already in the HH:mm format, return as is
                            if (typeof timeField === 'string' && timeField.length === 5) return timeField;
                            // If it's a string like "2023-06-03T07:00:00.000000Z", extract the time
                            if (typeof timeField === 'string' && timeField.includes('T')) {
                                const timePart = timeField.split('T')[1];
                                return timePart.substring(0, 5);
                            }
                            // If it's a string in HH:mm:ss format, extract the HH:mm part
                            if (typeof timeField === 'string' && timeField.length === 8) {
                                return timeField.substring(0, 5);
                            }
                            return timeField;
                        };
                        
                        // Process all time fields
                        setting.jam_mulai = formatTimeField(setting.jam_mulai);
                        setting.jam_selesai = formatTimeField(setting.jam_selesai);
                        setting.jam_istirahat_mulai = formatTimeField(setting.jam_istirahat_mulai);
                        setting.jam_istirahat_selesai = formatTimeField(setting.jam_istirahat_selesai);
                        setting.jam_istirahat2_mulai = formatTimeField(setting.jam_istirahat2_mulai);
                        setting.jam_istirahat2_selesai = formatTimeField(setting.jam_istirahat2_selesai);
                        
                        // Ensure numeric values for calculations
                        setting.jumlah_jam_pelajaran = parseInt(setting.jumlah_jam_pelajaran);
                        setting.durasi_per_jam = parseInt(setting.durasi_per_jam);
                        
                        // Save the processed setting
                        timeSlotSettings[setting.hari] = setting;
                        console.log(`Processed settings for ${setting.hari}:`, setting);
                    });
                    
                    console.log('All processed settings:', timeSlotSettings);
                    return true;
                }
                console.error('No valid settings found in response');
                return false;
            })
            .catch(error => {
                console.error('Error fetching settings:', error);
                alert('Gagal mengambil pengaturan jadwal: ' + error.message);
                return false;
            });
    }    // Load jadwal for current filter    
    function loadScheduleData() {        
        const kelasId = filterKelas.value;
        let semester = filterSemester.value;
        const tahunAjaran = filterTahun.value;
        
        // Validate inputs
        if (!kelasId) {
            alert('Pilih kelas terlebih dahulu!');
            return;
        }
        
        if (!tahunAjaran) {
            alert('Tahun ajaran tidak boleh kosong!');
            return;
        }
        
        // Make sure semester is always a numeric value
        semester = semester === '1' || semester === '2' ? semester : '1';
        
        console.log('Loading schedule with params:', { kelasId, semester, tahunAjaran });
        loadingSchedule.classList.remove('hidden');
        
        // Use POST instead of GET to ensure parameters are sent correctly
        fetch('/admin/jadwal/get-schedule', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                kelas_id: kelasId,
                semester: semester,
                tahun_ajaran: tahunAjaran
            })        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network response error: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response from server:', data);
                loadingSchedule.classList.add('hidden');
                if (data.success) {
                    // Store schedules
                    currentSchedules = {};
                    if (data.schedules && data.schedules.length > 0) {
                        data.schedules.forEach(j => {
                            if (!currentSchedules[j.hari]) currentSchedules[j.hari] = {};
                            
                            // Handle potentially nested or directly accessible fields
                            let mapelNama = '';
                            if (j.mapel_nama) {
                                mapelNama = j.mapel_nama;
                            } else if (j.mapel && j.mapel.nama) {
                                mapelNama = j.mapel.nama;
                            }
                            
                            let guruNama = '';
                            if (j.guru_nama) {
                                guruNama = j.guru_nama;
                            } else if (j.guru && j.guru.nama) {
                                guruNama = j.guru.nama;
                            }
                            
                            currentSchedules[j.hari][j.jam_ke] = {
                                id: j.id,
                                mapel_id: j.mapel_id,
                                guru_id: j.guru_id,
                                keterangan: j.keterangan,
                                is_active: j.is_active,
                                mapel: mapelNama,
                                guru: guruNama
                            };
                        });
                    }
                    console.log('Processed schedule data:', currentSchedules);
                    generateAllScheduleTables();
                } else {
                    alert(data.message || 'Gagal memuat jadwal!');
                }
            })
            .catch((error) => {
                console.error('Fetch error:', error);
                loadingSchedule.classList.add('hidden');
                alert('Terjadi kesalahan saat memuat jadwal: ' + error.message);
            });
    }

    // Generate table for all days
    function generateAllScheduleTables() {
        ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'].forEach(hari => {
            generateDaySchedule(hari);
        });    }
      // Generate table for a specific day
    function generateDaySchedule(hari) {
        console.log(`Generating schedule for ${hari}...`);
        const tbody = document.getElementById(`scheduleBody${hari}`);
        if (!tbody) {
            console.error(`Table body for ${hari} not found!`);
            return;
        }
        
        tbody.innerHTML = '';
        
        const settings = timeSlotSettings[hari];
        console.log(`Settings for ${hari}:`, settings);
        
        // If no settings for this day, show message with link
        if (!settings) {
            tbody.innerHTML = `<tr><td colspan="3">...</td></tr>`;
            return;
        }

        // Calculate all time slots upfront
        let timeSlots = [];
        let currentTime = settings.jam_mulai;
        const totalJam = parseInt(settings.jumlah_jam_pelajaran) || 0;
        const durasi = parseInt(settings.durasi_per_jam) || 35; // Default to 35 minutes if not set

        // Pre-calculate all time slots
        for (let jamKe = 1; jamKe <= totalJam; jamKe++) {
            let isBreakSlot = false;

            // Check for breaks before adding regular slot
            if (settings.jam_istirahat_mulai && currentTime === settings.jam_istirahat_mulai) {
                timeSlots.push({
                    type: 'break',
                    start: settings.jam_istirahat_mulai,
                    end: settings.jam_istirahat_selesai,
                    label: 'Istirahat'
                });
                currentTime = settings.jam_istirahat_selesai;
                isBreakSlot = true;
            }
            
            if (settings.jam_istirahat2_mulai && currentTime === settings.jam_istirahat2_mulai) {
                timeSlots.push({
                    type: 'break',
                    start: settings.jam_istirahat2_mulai,
                    end: settings.jam_istirahat2_selesai,
                    label: 'Istirahat 2'
                });
                currentTime = settings.jam_istirahat2_selesai;
                isBreakSlot = true;
            }

            if (!isBreakSlot) {
                // Calculate end time for regular slot
                const endTime = addMinutes(currentTime, durasi);
                timeSlots.push({
                    type: 'regular',
                    jamKe: jamKe,
                    start: currentTime,
                    end: endTime
                });
                currentTime = endTime;
            } else {
                // If this was a break slot, we need to still account for this jam_ke
                jamKe--;
            }
        }

        // Render the slots
        timeSlots.forEach(slot => {
            if (slot.type === 'break') {
                tbody.insertAdjacentHTML('beforeend', `
                    <tr class="bg-blue-50">
                        <td colspan="3" class="px-4 py-3 text-center text-blue-600">
                            <i class="fas fa-coffee mr-2"></i> ${slot.label}
                            (${slot.start} - ${slot.end})
                        </td>
                    </tr>`);
            } else {
                const jadwalData = currentSchedules[hari]?.[slot.jamKe];
                const row = document.createElement('tr');
                row.className = 'border-b border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors duration-150';
                
                row.innerHTML = `
                    <td class="px-4 py-3 text-sm border-r border-gray-200 text-center font-medium">
                        ${slot.jamKe}
                    </td>
                    <td class="px-4 py-3 text-sm border-r border-gray-200">
                        ${slot.start} - ${slot.end}
                    </td>
                    <td class="px-4 py-4 relative group">
                        ${jadwalData ? `
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900 ${!jadwalData.is_active ? 'line-through' : ''}">
                                        ${jadwalData.mapel || '-'}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        ${jadwalData.guru || 'Belum ada guru'}
                                    </div>
                                    ${jadwalData.keterangan ? `
                                        <div class="mt-1 text-xs text-gray-500">
                                            <i class="fas fa-info-circle mr-1"></i>${jadwalData.keterangan}
                                        </div>
                                    ` : ''}
                                </div>
                                <div class="opacity-0 group-hover:opacity-100 ml-4">
                                    <button type="button" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        ` : `
                            <div class="flex items-center justify-center text-gray-500 group-hover:text-blue-600">
                                <i class="fas fa-plus-circle mr-2"></i>
                                <span>Tambah Jadwal Pelajaran</span>
                            </div>
                        `}
                    </td>
                `;

                row.addEventListener('click', () => openModal(hari, slot.jamKe, slot.start, slot.end, jadwalData));
                tbody.appendChild(row);
            }
        });

        if (timeSlots.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center">
                        <div class="flex flex-col items-center text-gray-500">
                            <i class="fas fa-exclamation-circle text-xl mb-2"></i>
                            <p>Tidak ada slot jadwal yang tersedia</p>
                            <p class="text-sm">Periksa pengaturan jam dan durasi di pengaturan jadwal</p>
                        </div>
                    </td>
                </tr>`;
        }
    }    // Utility: add minutes to HH:mm string
    function addMinutes(time, minsToAdd) {
        try {
            if (!time || typeof time !== 'string' || !time.includes(':')) {
                console.error('Invalid time format:', time);
                return '00:00';
            }
            
            let [hours, minutes] = time.split(':').map(Number);
            if (isNaN(hours) || isNaN(minutes)) {
                console.error('Invalid time components:', { hours, minutes });
                return '00:00';
            }

            minutes += Number(minsToAdd);
            hours += Math.floor(minutes / 60);
            minutes = minutes % 60;
            hours = hours % 24; // Handle day overflow

            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
        } catch (error) {
            console.error('Error in addMinutes:', error);
            return '00:00';
        }
    }

    // --- Form validation and error handling ---
    function showModalError(message) {
        document.getElementById('modalErrorMessage').textContent = message;
        document.getElementById('modalError').classList.remove('hidden');
    }

    // --- Modal form submission ---
    document.getElementById('jadwalForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const jadwalId = formData.get('id');
        
        // Debug data yang akan dikirim
        console.log('Form data yang akan dikirim:', {
            id: formData.get('id'),
            kelas_id: formData.get('kelas_id'),
            mapel_id: formData.get('mapel_id'),
            guru_id: formData.get('guru_id'),
            hari: formData.get('hari'),
            jam_ke: formData.get('jam_ke'),
            jam_mulai: formData.get('jam_mulai'),
            jam_selesai: formData.get('jam_selesai'),
            semester: formData.get('semester'),
            tahun_ajaran: formData.get('tahun_ajaran'),
            keterangan: formData.get('keterangan'),            is_active: formData.get('is_active') === '1'
        });
        
        // Basic validation
        if (!formData.get('mapel_id')) {
            showModalError('Silakan pilih mata pelajaran');
            return;
        }
        if (!formData.get('guru_id')) {
            showModalError('Silakan pilih guru pengajar');
            return;
        }
        
        // Set method and URL
        let url = '/admin/jadwal';
        let method = 'POST';
        if (jadwalId) {
            url += '/' + jadwalId;
            method = 'POST';
            formData.append('_method', 'PUT');
        }

        console.log('Sending request to:', url, 'with method:', method);

        // Disable submit button and show loading state
        const submitBtn = document.getElementById('submitJadwal');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => {
            // Debug response
            console.log('Response status:', res.status);
            return res.json().then(data => {
                console.log('Response body:', data);
                if (!res.ok) {
                    return Promise.reject(data);
                }
                return data;
            });
        })
        .then(data => {
            if (data.success) {
                closeModal();
                loadScheduleData();

                // Show success notification
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg';
                toast.innerHTML = `<i class="fas fa-check-circle mr-2"></i>Jadwal berhasil disimpan`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            } else {
                showModalError(data.message || 'Terjadi kesalahan saat menyimpan jadwal');
            }
        })
        .catch(error => {
            console.error('Error details:', error);
            showModalError(error.message || 'Terjadi kesalahan saat menyimpan jadwal');
        })
        .finally(() => {
            // Reset submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Simpan';
        });
    });

    // --- Delete jadwal ---
    document.getElementById('deleteJadwal').addEventListener('click', function() {
        const jadwalId = document.getElementById('jadwal_id').value;
        if (!jadwalId) return;
        if (!confirm('Hapus jadwal ini?')) return;
        fetch(`/admin/jadwal/${jadwalId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeModal();
                loadScheduleData();
            } else {
                document.getElementById('modalErrorMessage').textContent = data.message || 'Terjadi kesalahan saat menghapus jadwal';
                document.getElementById('modalError').classList.remove('hidden');
            }
        })
        .catch(() => {
            document.getElementById('modalErrorMessage').textContent = 'Terjadi kesalahan saat menghapus jadwal';
            document.getElementById('modalError').classList.remove('hidden');
        });
    });

    // --- Modal close on background click ---
    document.getElementById('jadwalModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    // --- Load settings and schedule on page load ---
    fetchJadwalSettings().then(success => {
        if (success) {
            if (filterKelas.value) loadScheduleData();
        } else {
            alert('Gagal memuat pengaturan jadwal. Silakan periksa pengaturan jadwal terlebih dahulu.');
        }
    });    loadScheduleBtn.addEventListener('click', function() {
        fetchJadwalSettings().then(success => {
            if (success) loadScheduleData();
        });
    });
    
    // Function to create default settings if none exist
    function createDefaultSettings() {
        console.log('Creating default jadwal settings...');
        const defaultSettings = [
            {
                hari: 'Senin',
                jam_mulai: '07:00:00',
                jam_selesai: '15:30:00',
                jam_istirahat_mulai: '10:00:00',
                jam_istirahat_selesai: '10:15:00',
                jam_istirahat2_mulai: '12:00:00',
                jam_istirahat2_selesai: '12:45:00',
                jumlah_jam_pelajaran: 9,
                durasi_per_jam: 45,
                is_active: true
            },
            {
                hari: 'Selasa',
                jam_mulai: '07:00:00',
                jam_selesai: '15:30:00',
                jam_istirahat_mulai: '10:00:00',
                jam_istirahat_selesai: '10:15:00',
                jam_istirahat2_mulai: '12:00:00',
                jam_istirahat2_selesai: '12:45:00',
                jumlah_jam_pelajaran: 9,
                durasi_per_jam: 45,
                is_active: true
            },
            {
                hari: 'Rabu',
                jam_mulai: '07:00:00',
                jam_selesai: '15:30:00',
                jam_istirahat_mulai: '10:00:00',
                jam_istirahat_selesai: '10:15:00',
                jam_istirahat2_mulai: '12:00:00',
                jam_istirahat2_selesai: '12:45:00',
                jumlah_jam_pelajaran: 9,
                durasi_per_jam: 45,
                is_active: true
            },
            {
                hari: 'Kamis',
                jam_mulai: '07:00:00',
                jam_selesai: '15:30:00',
                jam_istirahat_mulai: '10:00:00',
                jam_istirahat_selesai: '10:15:00',
                jam_istirahat2_mulai: '12:00:00',
                jam_istirahat2_selesai: '12:45:00',
                jumlah_jam_pelajaran: 9,
                durasi_per_jam: 45,
                is_active: true
            },
            {
                hari: 'Jumat',
                jam_mulai: '07:00:00',
                jam_selesai: '11:30:00',
                jam_istirahat_mulai: '09:30:00',
                jam_istirahat_selesai: '09:45:00',
                jam_istirahat2_mulai: null,
                jam_istirahat2_selesai: null,
                jumlah_jam_pelajaran: 6,
                durasi_per_jam: 45,
                is_active: true
            }
        ];
          // Create all settings via API call
        return fetch('/admin/settings/jadwal/batch', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ settings: defaultSettings })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Settings creation response:', data);
            return data.success;
        })
        .catch(error => {
            console.error('Error creating settings:', error);
            return false;
        });
    }
});
</script>
@endpush
@endsection