<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda;
use App\Models\JadwalPelajaran;
use App\Models\Pengumuman;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function getEvents(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        $guru = auth()->guard('guru')->user();
        
        // Set date range for the month
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        // Get agenda events
        $agendaEvents = Agenda::where('is_active', true)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_mulai', [$startDate, $endDate])
                      ->orWhereBetween('tanggal_selesai', [$startDate, $endDate]);
            })
            ->get()
            ->map(function($agenda) {
                return [
                    'date' => $agenda->tanggal_mulai->format('Y-m-d'),
                    'type' => 'agenda',
                    'title' => $agenda->judul,
                    'description' => $agenda->deskripsi,
                    'time' => $agenda->tanggal_mulai->format('H:i')
                ];
            });
            
        // Get jadwal events (schedule for the teacher)
        $jadwalEvents = JadwalPelajaran::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->whereNotNull('hari')
            ->get()
            ->flatMap(function($jadwal) use ($startDate, $endDate) {
                $events = [];
                $currentDate = $startDate->copy();
                
                // Generate events for each occurrence of the day in the month
                while ($currentDate->lte($endDate)) {
                    $dayName = strtolower($currentDate->format('l'));
                    if ($dayName === strtolower($jadwal->hari)) {
                        $events[] = [
                            'date' => $currentDate->format('Y-m-d'),
                            'type' => 'jadwal',
                            'title' => $jadwal->mapel->nama_mapel ?? 'Mata Pelajaran',
                            'description' => 'Kelas: ' . ($jadwal->kelas->nama_kelas ?? 'N/A'),
                            'time' => $jadwal->jam_mulai . ' - ' . $jadwal->jam_selesai
                        ];
                    }
                    $currentDate->addDay();
                }
                return $events;
            });
            
        // Get pengumuman events (announcements with specific dates)
        $pengumumanEvents = Pengumuman::where('is_active', true)
            ->where(function($query) {
                $query->where('target_role', 'guru')
                      ->orWhere('target_role', 'all');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function($pengumuman) {
                return [
                    'date' => $pengumuman->created_at->format('Y-m-d'),
                    'type' => 'pengumuman',
                    'title' => $pengumuman->judul,
                    'description' => strip_tags($pengumuman->isi),
                    'time' => $pengumuman->created_at->format('H:i')
                ];
            });
            
        // Combine all events
        $calendarEvents = $agendaEvents->concat($jadwalEvents)->concat($pengumumanEvents)
            ->groupBy('date')
            ->map(function($events, $date) {
                return [
                    'date' => $date,
                    'events' => $events->toArray(),
                    'hasEvents' => true
                ];
            });
            
        return response()->json($calendarEvents);
    }
}
