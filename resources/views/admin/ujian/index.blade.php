@extends('layouts.ujian')

@section('title', 'Dashboard Ujian')

@section('content')
<div class="mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Ujian</h1>
            <p class="text-gray-600 mt-1">Manajemen dan monitoring ujian sekolah</p>
        </div>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Ujian</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Ujian Aktif -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clipboard-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Ujian Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeExams ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Peserta Ujian Hari Ini -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Peserta Ujian Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $todayParticipants ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Bank Soal -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Bank Soal</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalQuestions ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Nilai Rata-rata -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Nilai Rata-rata</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($averageScore ?? 0, 1) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Quick Menu -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.ujian.bank-soal.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-book text-blue-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Bank Soal</span>
                </a>
                <a href="{{ route('admin.ujian.jadwal.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-calendar-alt text-green-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Jadwal Ujian</span>
                </a>
                <a href="{{ route('admin.ujian.monitoring') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-desktop text-purple-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Monitoring</span>
                </a>
                <a href="{{ route('admin.ujian.hasil.kelas') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-file-alt text-orange-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Hasil Ujian</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-3">
                @if(isset($todayExams) && $todayExams->count() > 0)
                    @foreach($todayExams as $exam)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-file-alt text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $exam->mata_pelajaran }}</p>
                            <p class="text-xs text-gray-500">{{ $exam->kelas }} • {{ $exam->waktu }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $exam->status_color == 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $exam->status }}
                        </span>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-500">Tidak ada ujian hari ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Statistics Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Ujian</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-chart-bar text-gray-400 text-3xl mb-2"></i>
                    <p class="text-gray-500">Chart akan ditampilkan di sini</p>
                </div>
            </div>
        </div>

        <!-- Performance Overview -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Performa</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Ujian Selesai</span>
                    <span class="text-sm font-medium text-gray-900">{{ $completedExams ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Ujian Berlangsung</span>
                    <span class="text-sm font-medium text-gray-900">{{ $activeExams ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Peserta</span>
                    <span class="text-sm font-medium text-gray-900">{{ $totalParticipants ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Tingkat Kelulusan</span>
                    <span class="text-sm font-medium text-green-600">{{ $passRate ?? 0 }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi grafik nilai per mata pelajaran
    var chartNilaiMapel = new ApexCharts(document.querySelector("#chart-nilai-mapel"), {
        series: [{
            name: 'Nilai Rata-rata',
            data: @json($chartData['averages'] ?? [])
        }],
        chart: {
            type: 'bar',
            height: 350
        },
        xaxis: {
            categories: @json($chartData['subjects'] ?? []),
            position: 'bottom',
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        yaxis: {
            title: {
                text: 'Nilai'
            },
            min: 0,
            max: 100
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val.toFixed(1)
                }
            }
        }
    });
    chartNilaiMapel.render();
});
</script>
@endsection
