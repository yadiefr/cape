@extends('layouts.admin')

@section('title', 'Pengaturan - SMK PGRI CIKAMPEK')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex flex-col space-y-6">
        <!-- Page Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 relative overflow-hidden">
            <div class="absolute right-0 -top-12 h-40 w-40 bg-blue-100 opacity-50 rounded-full"></div>
            <div class="absolute -right-8 top-20 h-20 w-20 bg-blue-200 opacity-30 rounded-full"></div>
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-cog text-blue-600 mr-3"></i>
                            Pengaturan
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">Kelola pengaturan website dan sekolah</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex items-center gap-4">
                        <div class="relative">
                            <input type="text" 
                                   id="search-settings" 
                                   placeholder="Cari pengaturan..." 
                                   class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <select id="filter-group" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Grup</option>
                            <option value="sekolah">Sekolah</option>
                            <option value="sistem">Sistem</option>
                            <option value="website">Website</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Form -->
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Pengaturan Sekolah -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden settings-item" data-group="sekolah">
                    <div class="p-4 bg-blue-50 border-b border-gray-100">
                        <h3 class="font-medium text-gray-700 flex items-center">
                            <i class="fas fa-school text-blue-600 mr-2"></i>
                            Pengaturan Sekolah
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Sekolah
                                    </label>
                                    <p class="text-sm text-gray-500">Nama resmi sekolah Anda.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <input type="text" 
                                           name="settings[nama_sekolah][value]" 
                                           value="{{ $settings['nama_sekolah'] ?? 'SMK PGRI CIKAMPEK' }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <input type="hidden" name="settings[nama_sekolah][key]" value="nama_sekolah">
                                    <input type="hidden" name="settings[nama_sekolah][group]" value="sekolah">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Alamat Sekolah
                                    </label>
                                    <p class="text-sm text-gray-500">Alamat lengkap sekolah.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <textarea 
                                        name="settings[alamat_sekolah][value]" 
                                        rows="3"
                                        placeholder="Masukkan alamat lengkap sekolah"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $settings['alamat_sekolah'] ?? '' }}</textarea>
                                    <input type="hidden" name="settings[alamat_sekolah][key]" value="alamat_sekolah">
                                    <input type="hidden" name="settings[alamat_sekolah][group]" value="sekolah">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nomor Telepon
                                    </label>
                                    <p class="text-sm text-gray-500">Nomor telepon sekolah.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <input type="text" 
                                           name="settings[telepon_sekolah][value]" 
                                           value="{{ $settings['telepon_sekolah'] ?? '' }}"
                                           placeholder="Contoh: (0264) 123456"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <input type="hidden" name="settings[telepon_sekolah][key]" value="telepon_sekolah">
                                    <input type="hidden" name="settings[telepon_sekolah][group]" value="sekolah">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Email Sekolah
                                    </label>
                                    <p class="text-sm text-gray-500">Email resmi sekolah.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <input type="email" 
                                           name="settings[email_sekolah][value]" 
                                           value="{{ $settings['email_sekolah'] ?? '' }}"
                                           placeholder="info@smkpgricikampek.sch.id"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <input type="hidden" name="settings[email_sekolah][key]" value="email_sekolah">
                                    <input type="hidden" name="settings[email_sekolah][group]" value="sekolah">
                                </div>
                            </div>                            <!-- Logo Sekolah -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo Sekolah</label>
                                    <p class="text-sm text-gray-500">Logo resmi sekolah (max 2MB).</p>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="space-y-2">                                        @if(isset($settings['logo_sekolah']) && !empty($settings['logo_sekolah']))
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $settings['logo_sekolah']) }}" 
                                                     alt="Logo Sekolah" 
                                                     class="h-20 w-auto object-contain border rounded"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/default-logo.png') }}'; console.log('Logo image failed to load from: {{ asset('storage/' . $settings['logo_sekolah']) }}');">
                                                <p class="text-xs text-gray-500 mt-1">Current logo: {{ $settings['logo_sekolah'] }}</p>
                                            </div>
                                        @else
                                            <div class="mb-2 p-4 border-2 border-dashed border-gray-300 rounded text-center">
                                                <i class="fas fa-image text-gray-400 text-2xl mb-2"></i>
                                                <p class="text-gray-500 text-sm">Belum ada logo yang diupload</p>
                                            </div>
                                        @endif
                                        
                                        <div class="relative">
                                            <input type="file" 
                                                   name="settings[logo_sekolah][value]" 
                                                   accept="image/*"
                                                   class="block w-full text-sm text-gray-500 
                                                          file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 
                                                          file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 
                                                          hover:file:bg-blue-100">
                                            <input type="hidden" name="settings[logo_sekolah][key]" value="logo_sekolah">
                                            <input type="hidden" name="settings[logo_sekolah][group]" value="sekolah">
                                            <input type="hidden" name="settings[logo_sekolah][type]" value="image">
                                        </div>
                                        
                                        @error('logo_sekolah')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengaturan Website -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden settings-item" data-group="website">
                    <div class="p-4 bg-green-50 border-b border-gray-100">
                        <h3 class="font-medium text-gray-700 flex items-center">
                            <i class="fas fa-globe text-green-600 mr-2"></i>
                            Pengaturan Website
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Judul Website
                                    </label>
                                    <p class="text-sm text-gray-500">Judul yang tampil di browser.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <input type="text" 
                                           name="settings[site_title][value]" 
                                           value="{{ $settings['site_title'] ?? 'SMK PGRI CIKAMPEK' }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <input type="hidden" name="settings[site_title][key]" value="site_title">
                                    <input type="hidden" name="settings[site_title][group]" value="website">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Deskripsi Website
                                    </label>
                                    <p class="text-sm text-gray-500">Deskripsi singkat untuk SEO.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <textarea 
                                        name="settings[site_description][value]" 
                                        rows="3"
                                        placeholder="Deskripsi website untuk mesin pencari"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $settings['site_description'] ?? '' }}</textarea>
                                    <input type="hidden" name="settings[site_description][key]" value="site_description">
                                    <input type="hidden" name="settings[site_description][group]" value="website">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Keywords
                                    </label>
                                    <p class="text-sm text-gray-500">Kata kunci untuk SEO (pisahkan dengan koma).</p>
                                </div>
                                <div class="md:col-span-2">
                                    <input type="text" 
                                           name="settings[site_keywords][value]" 
                                           value="{{ $settings['site_keywords'] ?? '' }}"
                                           placeholder="smk, sekolah kejuruan, cikampek, pendidikan"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <input type="hidden" name="settings[site_keywords][key]" value="site_keywords">
                                    <input type="hidden" name="settings[site_keywords][group]" value="website">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Favicon
                                    </label>
                                    <p class="text-sm text-gray-500">Icon website (16x16px atau 32x32px).</p>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="space-y-2">                                        @php
                                            $favicon = isset($settings['site_favicon']) ? $settings['site_favicon'] : null;
                                        @endphp
                                        @if($favicon && Storage::disk('public')->exists($favicon))
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $favicon) }}" 
                                                     alt="Favicon" 
                                                     class="h-8 w-8 object-contain border rounded">
                                            </div>
                                        @endif<input type="file" 
                                               name="site_favicon" 
                                               accept="image/*"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                        <input type="hidden" name="settings[site_favicon][key]" value="site_favicon">
                                        <input type="hidden" name="settings[site_favicon][group]" value="website">
                                        <input type="hidden" name="settings[site_favicon][type]" value="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengaturan Sistem -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden settings-item" data-group="sistem">
                    <div class="p-4 bg-purple-50 border-b border-gray-100">
                        <h3 class="font-medium text-gray-700 flex items-center">
                            <i class="fas fa-cogs text-purple-600 mr-2"></i>
                            Pengaturan Sistem
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Mode Maintenance
                                    </label>
                                    <p class="text-sm text-gray-500">Aktifkan untuk menonaktifkan website sementara.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <select name="settings[maintenance_mode][value]" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="0" {{ ($settings['maintenance_mode'] ?? '0') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                        <option value="1" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'selected' : '' }}>Aktif</option>
                                    </select>
                                    <input type="hidden" name="settings[maintenance_mode][key]" value="maintenance_mode">
                                    <input type="hidden" name="settings[maintenance_mode][group]" value="sistem">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Timezone
                                    </label>
                                    <p class="text-sm text-gray-500">Zona waktu sistem.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <select name="settings[timezone][value]" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                        <option value="Asia/Makassar" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                        <option value="Asia/Jayapura" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                                    </select>
                                    <input type="hidden" name="settings[timezone][key]" value="timezone">
                                    <input type="hidden" name="settings[timezone][group]" value="sistem">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Items Per Page
                                    </label>
                                    <p class="text-sm text-gray-500">Jumlah item per halaman di tabel.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <select name="settings[items_per_page][value]" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="10" {{ ($settings['items_per_page'] ?? '10') == '10' ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ ($settings['items_per_page'] ?? '10') == '25' ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ ($settings['items_per_page'] ?? '10') == '50' ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ ($settings['items_per_page'] ?? '10') == '100' ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input type="hidden" name="settings[items_per_page][key]" value="items_per_page">
                                    <input type="hidden" name="settings[items_per_page][group]" value="sistem">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengaturan Sosial Media -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden settings-item" data-group="website">
                    <div class="p-4 bg-pink-50 border-b border-gray-100">
                        <h3 class="font-medium text-gray-700 flex items-center">
                            <i class="fas fa-share-alt text-pink-600 mr-2"></i>
                            Sosial Media
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Facebook
                                    </label>
                                    <p class="text-sm text-gray-500">URL halaman Facebook sekolah.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <input type="url" 
                                           name="settings[facebook_url][value]" 
                                           value="{{ $settings['facebook_url'] ?? '' }}"
                                           placeholder="https://facebook.com/smkpgricikampek"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <input type="hidden" name="settings[facebook_url][key]" value="facebook_url">
                                    <input type="hidden" name="settings[facebook_url][group]" value="website">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Instagram
                                    </label>
                                    <p class="text-sm text-gray-500">URL Instagram sekolah.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <input type="url" 
                                           name="settings[instagram_url][value]" 
                                           value="{{ $settings['instagram_url'] ?? '' }}"
                                           placeholder="https://instagram.com/smkpgricikampek"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <input type="hidden" name="settings[instagram_url][key]" value="instagram_url">
                                    <input type="hidden" name="settings[instagram_url][group]" value="website">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        YouTube
                                    </label>
                                    <p class="text-sm text-gray-500">URL channel YouTube sekolah.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <input type="url" 
                                           name="settings[youtube_url][value]" 
                                           value="{{ $settings['youtube_url'] ?? '' }}"
                                           placeholder="https://youtube.com/@smkpgricikampek"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <input type="hidden" name="settings[youtube_url][key]" value="youtube_url">
                                    <input type="hidden" name="settings[youtube_url][group]" value="website">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        WhatsApp
                                    </label>
                                    <p class="text-sm text-gray-500">Nomor WhatsApp sekolah (format: 628xxx).</p>
                                </div>
                                <div class="md:col-span-2">
                                    <input type="text" 
                                           name="settings[whatsapp_number][value]" 
                                           value="{{ $settings['whatsapp_number'] ?? '' }}"
                                           placeholder="6281234567890"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <input type="hidden" name="settings[whatsapp_number][key]" value="whatsapp_number">
                                    <input type="hidden" name="settings[whatsapp_number][group]" value="website">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('search-settings').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        document.querySelectorAll('.settings-item').forEach(function(item) {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });

    document.getElementById('filter-group').addEventListener('change', function() {
        const groupValue = this.value;
        document.querySelectorAll('.settings-item').forEach(function(item) {
            const group = item.getAttribute('data-group');
            item.style.display = groupValue === '' || group === groupValue ? '' : 'none';
        });
    });
</script>
@endpush
