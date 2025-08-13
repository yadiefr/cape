<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BellController extends Controller
{
    /**
     * Display a listing of the school bells.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bells = \App\Models\SchoolBell::orderBy('time')->get();
        $groupedBells = $bells->groupBy('day_of_week');
        
        // Untuk bel yang tidak spesifik hari (setiap hari)
        $dailyBells = $groupedBells->get(null, collect());
        
        // Days of week untuk urutan tampilan
        $daysOfWeek = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        
        return view('admin.bells.index', compact('groupedBells', 'dailyBells', 'daysOfWeek'));
    }

    /**
     * Show the form for creating a new school bell.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $daysOfWeek = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        
        $bellTypes = [
            'regular' => 'Reguler',
            'break' => 'Istirahat',
            'exam' => 'Ujian',
            'special' => 'Khusus'
        ];
        
        $iconOptions = [
            'bell' => 'Bel',
            'coffee' => 'Istirahat',
            'book-open' => 'Belajar',
            'home' => 'Pulang',
            'user-clock' => 'Absensi',
            'flag-checkered' => 'Upacara',
            'book' => 'Ujian'
        ];
        
        return view('admin.bells.create', compact('daysOfWeek', 'bellTypes', 'iconOptions'));
    }

    /**
     * Store a newly created school bell in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'time' => 'required',
            'type' => 'required|string',
            'day_of_week' => 'nullable|string',
            'icon' => 'required|string',
            'color_code' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        
        // Handle file upload jika ada
        $soundFilePath = null;
        if ($request->hasFile('sound_file') && $request->file('sound_file')->isValid()) {
            $file = $request->file('sound_file');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/sounds'), $fileName);
            $soundFilePath = 'uploads/sounds/' . $fileName;
        }
        
        \App\Models\SchoolBell::create([
            'name' => $request->name,
            'day_of_week' => $request->day_of_week,
            'time' => $request->time,
            'sound_file' => $soundFilePath,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'type' => $request->type,
            'color_code' => $request->color_code ?? '#3B82F6',
            'icon' => $request->icon,
        ]);
        
        return redirect()->route('admin.bells.index')
            ->with('success', 'Jadwal bel berhasil ditambahkan!');
    }

    /**
     * Display the specified school bell.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bell = \App\Models\SchoolBell::findOrFail($id);
        return view('admin.bells.show', compact('bell'));
    }

    /**
     * Show the form for editing the specified school bell.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bell = \App\Models\SchoolBell::findOrFail($id);
        
        $daysOfWeek = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        
        $bellTypes = [
            'regular' => 'Reguler',
            'break' => 'Istirahat',
            'exam' => 'Ujian',
            'special' => 'Khusus'
        ];
        
        $iconOptions = [
            'bell' => 'Bel',
            'coffee' => 'Istirahat',
            'book-open' => 'Belajar',
            'home' => 'Pulang',
            'user-clock' => 'Absensi',
            'flag-checkered' => 'Upacara',
            'book' => 'Ujian'
        ];
        
        return view('admin.bells.edit', compact('bell', 'daysOfWeek', 'bellTypes', 'iconOptions'));
    }

    /**
     * Update the specified school bell in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'time' => 'required',
            'type' => 'required|string',
            'day_of_week' => 'nullable|string',
            'icon' => 'required|string',
            'color_code' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        
        $bell = \App\Models\SchoolBell::findOrFail($id);
        
        // Handle file upload jika ada
        if ($request->hasFile('sound_file') && $request->file('sound_file')->isValid()) {
            // Hapus file lama jika ada
            if ($bell->sound_file && file_exists(public_path($bell->sound_file))) {
                unlink(public_path($bell->sound_file));
            }
            
            $file = $request->file('sound_file');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/sounds'), $fileName);
            $bell->sound_file = 'uploads/sounds/' . $fileName;
        }
        
        $bell->update([
            'name' => $request->name,
            'day_of_week' => $request->day_of_week,
            'time' => $request->time,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'type' => $request->type,
            'color_code' => $request->color_code ?? '#3B82F6',
            'icon' => $request->icon,
        ]);
        
        return redirect()->route('admin.bells.index')
            ->with('success', 'Jadwal bel berhasil diperbarui!');
    }

    /**
     * Remove the specified school bell from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bell = \App\Models\SchoolBell::findOrFail($id);
        
        // Hapus file suara jika ada
        if ($bell->sound_file && file_exists(public_path($bell->sound_file))) {
            unlink(public_path($bell->sound_file));
        }
        
        $bell->delete();
        
        return redirect()->route('admin.bells.index')
            ->with('success', 'Jadwal bel berhasil dihapus!');
    }
    
    /**
     * Mengaktifkan atau menonaktifkan bel.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleActive($id)
    {
        $bell = \App\Models\SchoolBell::findOrFail($id);
        $bell->is_active = !$bell->is_active;
        $bell->save();
        
        return redirect()->back()
            ->with('success', 'Status bel berhasil diubah!');
    }
    
    /**
     * Menjalankan bel secara manual.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ringBell($id)
    {
        $bell = \App\Models\SchoolBell::findOrFail($id);
        
        // Implementasi logic untuk membunyikan bel disini
        // Ini bisa berupa event broadcasting, atau logging
        
        // Contoh sementara
        \Log::info('Bel dibunyikan manual: ' . $bell->name . ' pada ' . now()->format('H:i:s'));
        
        return redirect()->back()
            ->with('success', 'Bel "' . $bell->name . '" berhasil dibunyikan!');
    }
    
    /**
     * Tampilkan bel untuk hari ini di dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTodayBells()
    {
        $today = now()->format('l'); // Nama hari dalam bahasa Inggris (Monday, Tuesday, etc.)
        $bells = \App\Models\SchoolBell::active()
            ->where(function($query) use ($today) {
                $query->where('day_of_week', $today)
                      ->orWhereNull('day_of_week'); // Include bells for every day
            })
            ->orderBy('time')
            ->get();
            
        return response()->json($bells);
    }
}
