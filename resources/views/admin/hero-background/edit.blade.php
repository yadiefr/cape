@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Background Hero</h1>
            <a href="{{ route('admin.hero-background.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.hero-background.update', $heroBackground) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Background</label>
                    @if($heroBackground->image)
                        <div class="mb-2">
                            <p class="text-sm text-gray-600">Gambar saat ini:</p>
                            <img src="{{ $heroBackground->image_url }}" alt="Background preview" class="w-32 h-20 object-cover rounded border">
                        </div>
                    @endif
                    <input type="file" name="image" id="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar background.</p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="opacity" class="block text-sm font-medium text-gray-700 mb-1">Opacity (0.0 - 1.0)</label>
                    <input type="number" name="opacity" id="opacity" value="{{ old('opacity', $heroBackground->opacity ?? '0.8') }}" min="0" max="1" step="0.1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-sm text-gray-500">1.0 = Tidak transparan, 0.8 = Sedikit transparan (direkomendasikan), 0.5 = Transparan 50%</p>
                    @error('opacity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $heroBackground->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Aktifkan background ini</span>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
