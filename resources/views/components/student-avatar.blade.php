@props([
    'student',
    'size' => 'md', // sm, md, lg
    'class' => ''
])

@php
    $sizeClass = match($size) {
        'sm' => 'h-8 w-8',
        'md' => 'h-10 w-10', 
        'lg' => 'h-12 w-12',
        default => 'h-10 w-10'
    };
@endphp

<div class="flex-shrink-0 {{ $sizeClass }} {{ $class }}">
    <div class="{{ $sizeClass }} rounded-full overflow-hidden bg-gray-100 border-2 border-gray-200">
        @if($student->foto && Storage::disk('public')->exists($student->foto))
            <img src="{{ asset('storage/' . $student->foto) }}" 
                 alt="Foto {{ $student->nama_lengkap }}" 
                 class="h-full w-full object-cover">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->nama_lengkap) }}&background=3b82f6&color=ffffff" 
                 alt="Foto {{ $student->nama_lengkap }}" 
                 class="h-full w-full object-cover">
        @endif
    </div>
</div>
