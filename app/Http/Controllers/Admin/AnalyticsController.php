<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Pengumuman;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Get statistics for the dashboard
        $stats = $this->getStatistics();
        
        // Get monthly trends
        $monthlyTrends = $this->getMonthlyTrends();
        
        // Get attendance rates
        $attendanceData = $this->getAttendanceStatistics();

        return view('admin.analytics.index', compact('stats', 'monthlyTrends', 'attendanceData'));
    }

    private function getStatistics()
    {
        $now = Carbon::now();
        $monthStart = $now->startOfMonth();
        
        return [
            'total_students' => Siswa::count(),
            'active_students' => Siswa::where('status', 'aktif')->count(),
            'total_announcements' => Pengumuman::where('is_active', true)->count(),
            'total_classes' => Kelas::count(),
            'new_students' => Siswa::where('created_at', '>=', $monthStart)->count(),
            'attendance_today' => Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'hadir')
                ->count(),
            'attendance_rate' => $this->calculateAttendanceRate(),
        ];
    }
    
    private function calculateAttendanceRate()
    {
        $totalStudents = Siswa::where('status', 'aktif')->count();
        if ($totalStudents === 0) return 0;
        
        $presentToday = Absensi::whereDate('created_at', Carbon::today())
            ->where('status', 'hadir')
            ->count();
            
        return round(($presentToday / $totalStudents) * 100, 1);
    }

    private function getMonthlyTrends()
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        
        // Get monthly student registrations
        $registrations = Siswa::where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Get monthly announcements
        $announcements = Pengumuman::where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Format data for the last 6 months
        $months = [];
        $registrationData = [];
        $announcementData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $months[] = Carbon::now()->subMonths($i)->format('M Y');
            $registrationData[] = $registrations[$month] ?? 0;
            $announcementData[] = $announcements[$month] ?? 0;
        }

        return [
            'months' => $months,
            'registrations' => $registrationData,
            'announcements' => $announcementData,
        ];
    }

    private function getAttendanceStatistics()
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        
        // Get today's attendance
        $todayStats = Absensi::whereDate('tanggal', $today)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        // Get this week's attendance
        $weekStats = Absensi::whereBetween('tanggal', [$weekStart, $today->endOfDay()])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        // Calculate attendance rate
        $activeStudents = Siswa::where('status', 'aktif')->count();
        $attendanceRate = $activeStudents > 0 ? round(($todayStats['hadir'] ?? 0) / $activeStudents * 100, 1) : 0;
        $todayTotal = array_sum($todayStats);
        $weekTotal = array_sum($weekStats);
        
        return [
            'week' => [
                'hadir' => $weekStats['hadir'] ?? 0,
                'izin' => $weekStats['izin'] ?? 0,
                'sakit' => $weekStats['sakit'] ?? 0,
                'alpha' => $weekStats['alpha'] ?? 0,
                'total' => $weekTotal,
                'attendance_rate' => $weekTotal > 0 ? round(($weekStats['hadir'] ?? 0) / $weekTotal * 100, 1) : 0
            ],
            'today' => [
                'hadir' => $todayStats['hadir'] ?? 0,
                'izin' => $todayStats['izin'] ?? 0,
                'sakit' => $todayStats['sakit'] ?? 0,
                'alpha' => $todayStats['alpha'] ?? 0,
                'total' => $todayTotal,
                'attendance_rate' => $attendanceRate
            ],
        ];
    }

    private function getWeeklyAttendanceData()
    {
        $data = [];
        $labels = [];
        $now = Carbon::now();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $count = Absensi::whereDate('created_at', $date)
                ->where('status', 'hadir')
                ->count();
                
            $data[] = $count;
            $labels[] = $date->isoFormat('dddd');
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getMonthlyAttendanceData()
    {
        $data = [];
        $labels = [];
        $now = Carbon::now();
        $daysInMonth = $now->daysInMonth;
        
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::create($now->year, $now->month, $i);
            if ($date->isFuture()) break;
            
            $count = Absensi::whereDate('created_at', $date)
                ->where('status', 'hadir')
                ->count();
                
            $data[] = $count;
            $labels[] = $date->format('d');
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function getAttendanceData(Request $request)
    {
        $period = $request->period ?? 'week';
        
        $data = $period === 'week' 
            ? $this->getWeeklyAttendanceData()
            : $this->getMonthlyAttendanceData();
            
        return response()->json($data);
    }
}