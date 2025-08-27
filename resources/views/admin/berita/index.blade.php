@extends('layouts.admin')

@section('title', 'Daftar Berita')

@section('main-content')
<!-- Flash Messages -->
@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
        <i class="fas fa-times cursor-pointer text-green-700 hover:text-green-900"></i>
    </span>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
        <i class="fas fa-times cursor-pointer text-red-700 hover:text-red-900"></i>
    </span>
</div>
@endif

<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-newspaper text-blue-600 mr-3"></i>
                Daftar Berita
            </h1>
            <p class="text-gray-600 mt-1">Total: {{ $berita->count() }} berita</p>
        </div>
        <a href="{{ route('admin.berita.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Berita
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thumbnail</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($berita as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="h-16 w-20 flex-shrink-0">
                            @if($item->foto)
                                <img class="h-16 w-20 rounded-lg object-cover shadow-sm border border-gray-200" 
                                     src="{{ asset('storage/' . $item->foto) }}" 
                                     alt="Thumbnail {{ $item->judul }}"
                                     onerror="this.onerror=null; this.parentNode.innerHTML='<div class=\'h-16 w-20 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200\'><i class=\'fas fa-image text-gray-400 text-xl\'></i></div>';">
                            @else
                                <div class="h-16 w-20 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                    <i class="fas fa-image text-gray-400 text-xl"></i>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $item->judul }}</div>
                        <div class="text-sm text-gray-500 mt-1">{{ Str::limit(strip_tags($item->isi), 80) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.berita.edit', $item->id) }}" 
                               class="inline-flex items-center px-3 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-xs font-medium rounded-md transition-colors duration-150">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" class="inline-block" id="delete-form-{{ $item->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-800 text-xs font-medium rounded-md transition-colors duration-150"
                                        onclick="confirmDelete(event, document.getElementById('delete-form-{{ $item->id }}'), '{{ addslashes($item->judul) }}')">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 text-sm">Belum ada berita</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
// Konfirmasi hapus dengan SweetAlert style (jika tersedia) atau confirm biasa
function confirmDelete(event, form, title) {
    event.preventDefault();
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Berita "${title}" akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    } else {
        if (confirm(`Apakah Anda yakin ingin menghapus berita "${title}"?\n\nData yang dihapus tidak dapat dikembalikan.`)) {
            form.submit();
        }
    }
}
</script>
@endsection
