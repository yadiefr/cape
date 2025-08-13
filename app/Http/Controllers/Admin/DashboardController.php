<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use App\Models\Jurusan;
use App\Models\Pengumuman;
use App\Models\Berita;
use App\Models\GaleriFoto;
use App\Models\BelSekolah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();
        $totalJurusan = Jurusan::count();
        $totalJadwal = JadwalPelajaran::count();
        
        // Get latest agendas for the dashboard
        $agenda = Agenda::latest()->take(5)->get();
        
        // Get latest announcements
        $pengumuman = Pengumuman::with('author')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get today's bells - Mendapatkan bel untuk hari ini
        // Konversi nama hari ke Bahasa Indonesia
        $englishDay = now()->format('l');
        $indonesianDays = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $today = $indonesianDays[$englishDay] ?? $englishDay; // Nama hari dalam bahasa Indonesia
        
        $todayBells = BelSekolah::aktif()
            ->where(function($query) use ($today) {
                $query->where('hari', $today)
                      ->orWhereNull('hari'); // Include bells for every day
            })
            ->orderBy('waktu')
            ->take(4)
            ->get();
            
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [150, 200, 180, 250, 220, 300],
        ];
        
        // Collect recent activities from various sources
        $activities = collect();

        // Get latest news
        $latestBerita = Berita::latest()->take(3)->get();
        foreach ($latestBerita as $berita) {
            $activities->push([
                'type' => 'berita',
                'title' => $berita->judul,
                'date' => $berita->created_at,
                'icon' => 'newspaper',
                'color' => 'blue',
                'url' => route('admin.berita.edit', $berita->id)
            ]);
        }

        // Get latest announcements
        $latestPengumuman = Pengumuman::latest()->take(3)->get();
        foreach ($latestPengumuman as $p) {
            $activities->push([
                'type' => 'pengumuman',
                'title' => $p->judul,
                'date' => $p->created_at,
                'icon' => 'bullhorn',
                'color' => 'yellow',
                'url' => route('admin.pengumuman.edit', $p->id)
            ]);
        }

        // Get latest agendas
        $latestAgenda = Agenda::latest()->take(3)->get();
        foreach ($latestAgenda as $a) {
            $activities->push([
                'type' => 'agenda',
                'title' => $a->judul,
                'date' => $a->created_at,
                'icon' => 'calendar',
                'color' => 'green',
                'url' => route('admin.agenda.edit', $a->id)
            ]);
        }

        // Get latest gallery photos
        $latestGaleri = GaleriFoto::latest()->take(3)->get();
        foreach ($latestGaleri as $foto) {
            $activities->push([
                'type' => 'galeri',
                'title' => $foto->caption ?? 'Foto Baru',
                'date' => $foto->created_at,
                'icon' => 'image',
                'color' => 'purple',
                'url' => route('admin.galeri.index')
            ]);
        }

        // Sort all activities by date
        $activities = $activities->sortByDesc('date')->take(10);
        
        return view('admin.dashboard', compact(
            'totalSiswa', 
            'totalGuru', 
            'totalKelas', 
            'totalJurusan', 
            'totalJadwal',
            'todayBells', 
            'pengumuman', 
            'chartData', 
            'activities', 
            'agenda'
        ));
    }
}
