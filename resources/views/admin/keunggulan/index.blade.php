@extends('layouts.admin')

@section('content')
<div class="w-full px-3 py-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Keunggulan</h1>
        <a href="{{ route('admin.keunggulan.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Tambah Keunggulan
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ikon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($keunggulan as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->judul }}</td>
                        <td class="px-6 py-4">{{ Str::limit($item->deskripsi, 100) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->ikon }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.keunggulan.toggle-status', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 rounded {{ $item->is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.keunggulan.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <form action="{{ route('admin.keunggulan.destroy', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus keunggulan ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada keunggulan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 