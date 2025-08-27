@extends('layouts.admin')

@section('title', 'Pengaturan Jadwal Pelajaran')

@section('main-content')
<div class="w-full px-3 py-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pengaturan Jadwal Pelajaran</h1>
        <button onclick="toggleForm()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Tambah Pengaturan
        </button>
    </div>

    <p class="text-gray-600 mb-4">Di halaman ini, Anda dapat mengatur hari, jam mulai dan selesai, jam istirahat, dan pengaturan lainnya untuk jadwal pelajaran.</p>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">Ada beberapa masalah dengan input Anda:</span>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form untuk menambah pengaturan jadwal -->
    <div id="addForm" class="hidden bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-semibold mb-4">Tambah Pengaturan Jadwal</h2>        <form action="{{ route('admin.settings.jadwal.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="hari" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                    <select name="hari" id="hari" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                    @error('hari') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('jam_mulai') }}">
                    @error('jam_mulai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
 
                <div>
                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('jam_selesai') }}">
                    @error('jam_selesai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="jam_istirahat_mulai" class="block text-sm font-medium text-gray-700 mb-1">Jam Istirahat 1 Mulai</label>
                    <input type="time" name="jam_istirahat_mulai" id="jam_istirahat_mulai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('jam_istirahat_mulai') }}">
                    @error('jam_istirahat_mulai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="jam_istirahat_selesai" class="block text-sm font-medium text-gray-700 mb-1">Jam Istirahat 1 Selesai</label>
                    <input type="time" name="jam_istirahat_selesai" id="jam_istirahat_selesai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('jam_istirahat_selesai') }}">
                    @error('jam_istirahat_selesai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="jam_istirahat2_mulai" class="block text-sm font-medium text-gray-700 mb-1">Jam Istirahat 2 Mulai</label>
                    <input type="time" name="jam_istirahat2_mulai" id="jam_istirahat2_mulai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('jam_istirahat2_mulai') }}">
                    @error('jam_istirahat2_mulai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="jam_istirahat2_selesai" class="block text-sm font-medium text-gray-700 mb-1">Jam Istirahat 2 Selesai</label>                    <input type="time" name="jam_istirahat2_selesai" id="jam_istirahat2_selesai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ old('jam_istirahat2_selesai') }}">
                    @error('jam_istirahat2_selesai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="jumlah_jam_pelajaran" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Jam Pelajaran</label>
                    <input type="number" name="jumlah_jam_pelajaran" id="jumlah_jam_pelajaran" min="1" max="12" value="{{ old('jumlah_jam_pelajaran', 8) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="durasi_per_jam" class="block text-sm font-medium text-gray-700 mb-1">Durasi Per Jam (Menit)</label>
                    <input type="number" name="durasi_per_jam" id="durasi_per_jam" min="30" max="120" value="{{ old('durasi_per_jam', 45) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Simpan Pengaturan
                </button>
                <button type="button" onclick="toggleForm()" class="ml-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel pengaturan jadwal -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Istirahat 1</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Istirahat 2</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah JP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi/JP (menit)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if(count($jadwalSettings) > 0)
                    @forelse($jadwalSettings as $setting)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $setting->hari }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($setting->jam_mulai)->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($setting->jam_selesai)->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $setting->jam_istirahat_format ?? '-' }}
                        </td>                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $setting->jam_istirahat2_format ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $setting->jumlah_jam_pelajaran }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $setting->durasi_per_jam }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $setting->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $setting->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.settings.jadwal.edit', $setting->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>                            <form action="{{ route('admin.settings.jadwal.destroy', $setting->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus pengaturan ini?')">Hapus</button>
                            </form>                            <form action="{{ route('admin.settings.jadwal.toggle-status', $setting->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 ml-3">
                                    {{ $setting->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Belum ada pengaturan jadwal yang ditambahkan
                        </td>
                    </tr>
                    @endforelse
                @else
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Belum ada pengaturan jadwal yang ditambahkan
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function toggleForm() {
    const form = document.getElementById('addForm');
    form.classList.toggle('hidden');
}
</script>
@endpush
@endsection