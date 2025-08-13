@extends('layouts.admin')

@section('title', 'Kelola Mata Pelajaran - ' . $guru->nama)

@section('content')
<!-- SPA Container -->
<div id="assign-subjects-app" data-guru-id="{{ $guru->id }}"></div>

<!-- Fallback for non-JS users -->
<noscript>
<div class="container mx-auto px-4 py-6">
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
        <p class="font-medium">JavaScript Required</p>
        <p class="text-sm">This page requires JavaScript to function properly. Please enable JavaScript in your browser.</p>
    </div>
    
    <!-- Fallback to original form -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                <a href="{{ route('admin.guru.index') }}" class="hover:text-blue-600">Guru</a>
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                <a href="{{ route('admin.guru.show', $guru) }}" class="hover:text-blue-600">{{ $guru->nama }}</a>
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                <span>Kelola Mata Pelajaran</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-book-open text-blue-500 mr-3"></i> 
                Kelola Mata Pelajaran - {{ $guru->nama }}
            </h1>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.guru.show', $guru) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="text-center py-8">
        <p class="text-gray-500">Please enable JavaScript to use the full functionality of this page.</p>
    </div>
</div>
</noscript>
@endsection

@push('scripts')
@vite('resources/js/app.js')
@endpush
