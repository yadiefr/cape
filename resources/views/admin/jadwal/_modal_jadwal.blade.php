<!-- Modal -->
<div id="jadwalModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="jadwalForm">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalTitle">
                                Tambah Jadwal Pelajaran
                            </h3>
                            
                            <!-- Error Alert -->
                            <div id="modalError" class="hidden">
                                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <span id="modalErrorMessage">Error message here</span>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <!-- Info Slot -->
                                <div class="bg-blue-50 p-3 rounded-md mb-4">
                                    <p class="text-sm text-blue-700">
                                        <span class="font-medium">Slot:</span>
                                        <span id="slotInfo">Senin, 07:00 - 08:00</span>
                                    </p>
                                </div>

                                <!-- Hidden Fields -->
                                <input type="hidden" id="jadwal_id" name="id">
                                <input type="hidden" id="modal_hari" name="hari">
                                <input type="hidden" id="modal_jam_ke" name="jam_ke">
                                <input type="hidden" id="modal_jam_mulai" name="jam_mulai">
                                <input type="hidden" id="modal_jam_selesai" name="jam_selesai">
                                <input type="hidden" id="modal_kelas_id" name="kelas_id">
                                <input type="hidden" id="modal_semester" name="semester">
                                <input type="hidden" id="modal_tahun_ajaran" name="tahun_ajaran">
                                
                                <!-- Mata Pelajaran -->
                                <div>
                                    <label for="modal_mapel_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mata Pelajaran <span class="text-red-500">*</span>
                                    </label>
                                    <select id="modal_mapel_id" name="mapel_id" required 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($mapel_list as $m)
                                            <option value="{{ $m->id }}">
                                                {{ $m->nama }} {{ $m->jurusan ? '(' . $m->jurusan->nama . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Guru -->
                                <div>
                                    <label for="modal_guru_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Guru Pengajar <span class="text-red-500">*</span>
                                    </label>
                                    <select id="modal_guru_id" name="guru_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Pilih Guru</option>
                                        @foreach($guru_list as $g)
                                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Keterangan -->
                                <div>
                                    <label for="modal_keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Keterangan
                                    </label>
                                    <textarea id="modal_keterangan" name="keterangan"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            rows="2"></textarea>
                                </div>

                                <!-- Status -->                                <div class="flex items-center">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" id="modal_is_active" name="is_active" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" checked>
                                    <label for="modal_is_active" class="ml-2 block text-sm text-gray-900">
                                        Jadwal Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" id="submitJadwal" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                    <button type="button" id="deleteJadwal" class="hidden mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>