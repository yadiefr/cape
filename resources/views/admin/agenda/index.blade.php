@extends('layouts.admin')

@section('content')
<div class="w-full px-3 py-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Agenda</h1>
        <a href="{{ route('admin.agenda.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Tambah Agenda
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($agendas as $agenda)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $agenda->judul }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $agenda->tanggal_mulai->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $agenda->tanggal_selesai ? $agenda->tanggal_selesai->format('d/m/Y H:i') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $agenda->lokasi ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.agenda.toggle-status', $agenda) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm {{ $agenda->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $agenda->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.agenda.edit', $agenda) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <form action="{{ route('admin.agenda.destroy', $agenda) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 