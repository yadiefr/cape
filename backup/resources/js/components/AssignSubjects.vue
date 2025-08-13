<template>
  <div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb and actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
      <div>
        <div class="flex items-center text-sm text-gray-600 mb-2">
          <a href="/admin/dashboard" class="hover:text-blue-600">Dashboard</a>
          <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
          <a href="/admin/guru" class="hover:text-blue-600">Guru</a>
          <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
          <a :href="`/admin/guru/${guru.id}`" class="hover:text-blue-600">{{ guru.nama }}</a>
          <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
          <span>Kelola Mata Pelajaran</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
          <i class="fas fa-book-open text-blue-500 mr-3"></i> 
          Kelola Mata Pelajaran - {{ guru.nama }}
        </h1>
      </div>
      <div class="flex items-center space-x-3">
        <a :href="`/admin/guru/${guru.id}`" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition-all">
          <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
      </div>
    </div>

    <!-- Success/Error Messages -->
    <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ successMessage }}
    </div>

    <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ errorMessage }}
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
          <form @submit.prevent="addSubjectAssignment">
            <div class="mb-4">
              <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
              <select v-model="form.mapel_id" id="mapel_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                <option value="">Pilih Mata Pelajaran</option>
                <option v-for="subject in availableSubjects" :key="subject.id" :value="subject.id">
                  {{ subject.nama }}
                </option>
              </select>
              <p v-if="formErrors.mapel_id" class="text-red-500 text-sm mt-1">{{ formErrors.mapel_id[0] }}</p>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
              <div class="mb-2 flex gap-2">
                <button type="button" @click="selectAllClasses" class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 transition-all">
                  Pilih Semua
                </button>
                <button type="button" @click="clearAllClasses" class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded hover:bg-gray-200 transition-all">
                  Kosongkan
                </button>
              </div>
              <div class="border border-gray-300 rounded-lg p-3 max-h-80 overflow-y-auto">
                <label v-for="kelas in availableClasses" :key="kelas.id" class="flex items-center mb-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                  <input 
                    type="checkbox" 
                    :value="kelas.id" 
                    v-model="form.kelas_id"
                    class="mr-3 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                  <span class="text-sm text-gray-700">
                    {{ kelas.nama_kelas || 'Kelas tidak diketahui' }} - {{ kelas.jurusan?.nama_jurusan || kelas.jurusan?.nama || 'Jurusan tidak diketahui' }}
                  </span>
                </label>
              </div>
              <p class="text-sm text-gray-500 mt-1">Pilih satu atau beberapa kelas untuk mata pelajaran ini</p>
              <p v-if="formErrors.kelas_id" class="text-red-500 text-sm mt-1">{{ formErrors.kelas_id[0] }}</p>
            </div>

            <button 
              type="submit" 
              :disabled="isLoading"
              class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-all disabled:opacity-50">
              <i class="fas fa-spinner fa-spin mr-2" v-if="isLoading"></i>
              <i class="fas fa-plus mr-2" v-else></i> 
              {{ isLoading ? 'Menambahkan...' : 'Tambah Mata Pelajaran' }}
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
          <div v-if="isLoadingAssignments" class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-blue-500 text-2xl mb-4"></i>
            <p class="text-gray-500">Memuat data...</p>
          </div>
          
          <div v-else-if="assignedSubjects.length > 0" class="space-y-4">
            <div v-for="jadwal in assignedSubjects" :key="jadwal.id" class="bg-gray-50 rounded-lg border border-gray-200 p-4">
              <div class="flex justify-between items-start">
                <div class="flex-1">
                  <div class="flex items-center gap-2 mb-1">
                    <h4 class="font-medium text-gray-800">
                      {{ jadwal.mapel?.nama || 'Mata pelajaran tidak diketahui' }}
                    </h4>
                    <span v-if="jadwal.hari && jadwal.jam_ke" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      <i class="fas fa-calendar-check mr-1"></i>
                      Terjadwal
                    </span>
                    <span v-else class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                      <i class="fas fa-clock mr-1"></i>
                      Belum Terjadwal
                    </span>
                  </div>
                  <p class="text-sm text-gray-600 mb-2">
                    <i class="fas fa-school mr-1"></i>
                    {{ jadwal.kelas?.nama_kelas || 'Kelas tidak diketahui' }} - {{ jadwal.kelas?.jurusan?.nama_jurusan || jadwal.kelas?.jurusan?.nama || 'Jurusan tidak diketahui' }}
                  </p>
                  <p v-if="jadwal.hari && jadwal.jam_ke" class="text-sm text-gray-600 mb-1">
                    <i class="fas fa-calendar mr-1"></i>
                    {{ jadwal.hari }}, Jam ke-{{ jadwal.jam_ke }}
                    <span v-if="jadwal.jam_mulai && jadwal.jam_selesai">
                      ({{ jadwal.jam_mulai }} - {{ jadwal.jam_selesai }})
                    </span>
                  </p>
                </div>
                <button 
                  @click="removeSubjectAssignment(jadwal.id)"
                  :disabled="isLoading"
                  class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-all disabled:opacity-50 ml-4">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </div>
          </div>
          
          <div v-else class="text-center py-8">
            <i class="fas fa-book-open text-gray-300 text-4xl mb-4"></i>
            <p class="text-gray-500">Guru ini belum mengampu mata pelajaran apapun.</p>
            <p class="text-sm text-gray-400 mt-1">Tambahkan mata pelajaran menggunakan form di sebelah kiri.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: 'AssignSubjects',
  props: {
    guruId: {
      type: Number,
      required: true
    }
  },
  setup(props) {
    const guru = ref({})
    const assignedSubjects = ref([])
    const availableSubjects = ref([])
    const availableClasses = ref([])
    const isLoading = ref(false)
    const isLoadingAssignments = ref(true)
    const successMessage = ref('')
    const errorMessage = ref('')
    const formErrors = ref({})

    const form = reactive({
      mapel_id: '',
      kelas_id: []
    })

    // Load initial data
    const loadData = async () => {
      try {
        isLoadingAssignments.value = true
        const response = await axios.get(`/api/admin/guru/${props.guruId}/assign-subjects-data`)
        
        guru.value = response.data.guru
        assignedSubjects.value = response.data.assignedSubjects
        availableSubjects.value = response.data.availableSubjects
        availableClasses.value = response.data.availableClasses
      } catch (error) {
        console.error('Error loading data:', error)
        errorMessage.value = 'Gagal memuat data. Silakan refresh halaman.'
      } finally {
        isLoadingAssignments.value = false
      }
    }

    // Add subject assignment
    const addSubjectAssignment = async () => {
      if (form.kelas_id.length === 0) {
        errorMessage.value = 'Pilih minimal satu kelas!'
        return
      }

      try {
        isLoading.value = true
        formErrors.value = {}
        errorMessage.value = ''
        successMessage.value = ''

        const response = await axios.post(`/api/admin/guru/${props.guruId}/store-subject-assignment`, {
          mapel_id: form.mapel_id,
          kelas_id: form.kelas_id
        })

        successMessage.value = response.data.message
        
        // Reset form
        form.mapel_id = ''
        form.kelas_id = []
        
        // Reload assignments
        await loadData()
      } catch (error) {
        if (error.response?.status === 422) {
          formErrors.value = error.response.data.errors || {}
        }
        errorMessage.value = error.response?.data?.message || 'Terjadi kesalahan saat menambah mata pelajaran.'
      } finally {
        isLoading.value = false
      }
    }

    // Remove subject assignment
    const removeSubjectAssignment = async (assignmentId) => {
      if (!confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')) {
        return
      }

      try {
        isLoading.value = true
        errorMessage.value = ''
        successMessage.value = ''

        const response = await axios.delete(`/api/admin/guru/${props.guruId}/remove-subject-assignment/${assignmentId}`)
        
        successMessage.value = response.data.message
        
        // Reload assignments
        await loadData()
      } catch (error) {
        errorMessage.value = error.response?.data?.message || 'Terjadi kesalahan saat menghapus mata pelajaran.'
      } finally {
        isLoading.value = false
      }
    }

    // Select all classes
    const selectAllClasses = () => {
      form.kelas_id = availableClasses.value.map(kelas => kelas.id)
    }

    // Clear all classes
    const clearAllClasses = () => {
      form.kelas_id = []
    }

    // Clear messages after 5 seconds
    const clearMessages = () => {
      setTimeout(() => {
        successMessage.value = ''
        errorMessage.value = ''
      }, 5000)
    }

    onMounted(() => {
      loadData()
    })

    return {
      guru,
      assignedSubjects,
      availableSubjects,
      availableClasses,
      form,
      isLoading,
      isLoadingAssignments,
      successMessage,
      errorMessage,
      formErrors,
      addSubjectAssignment,
      removeSubjectAssignment,
      selectAllClasses,
      clearAllClasses
    }
  }
}
</script>
