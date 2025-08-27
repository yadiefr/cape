<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BelSekolahController extends Controller
{
    /**
     * Menampilkan daftar bel sekolah
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bels = \App\Models\BelSekolah::orderBy('waktu')->get();
        $groupedBels = $bels->groupBy('hari');
        
        // Untuk bel yang tidak spesifik hari (setiap hari)
        $dailyBels = $groupedBels->get(null, collect());
        
        // Daftar hari untuk urutan tampilan
        $daftarHari = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        
        return view('admin.bel.index', compact('groupedBels', 'dailyBels', 'daftarHari'));
    }

    /**
     * Menampilkan form untuk membuat bel sekolah baru
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $daftarHari = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        
        $tipeBel = [
            'reguler' => 'Reguler',
            'istirahat' => 'Istirahat',
            'ujian' => 'Ujian',
            'khusus' => 'Khusus'
        ];
        
        $pilihanIkon = [
            'bell' => 'Bel',
            'coffee' => 'Istirahat',
            'book-open' => 'Belajar',
            'home' => 'Pulang',
            'user-clock' => 'Absensi',
            'flag-checkered' => 'Upacara',
            'book' => 'Ujian'
        ];
        
        return view('admin.bel.create', compact('daftarHari', 'tipeBel', 'pilihanIkon'));
    }

    /**
     * Menyimpan bel sekolah baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'waktu' => 'required',
            'tipe' => 'required|string',
            'hari' => 'nullable|string',
            'ikon' => 'required|string',
            'kode_warna' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);
        
        // Handle file upload jika ada
        $fileSuaraPath = null;
        if ($request->hasFile('file_suara') && $request->file('file_suara')->isValid()) {
            $file = $request->file('file_suara');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/suara'), $fileName);
            $fileSuaraPath = 'uploads/suara/' . $fileName;
        }
        
        \App\Models\BelSekolah::create([
            'nama' => $request->nama,
            'hari' => $request->hari,
            'waktu' => $request->waktu,
            'file_suara' => $fileSuaraPath,
            'deskripsi' => $request->deskripsi,
            'aktif' => $request->has('aktif'),
            'tipe' => $request->tipe,
            'kode_warna' => $request->kode_warna ?? '#3B82F6',
            'ikon' => $request->ikon,
        ]);
        
        return redirect()->route('admin.bel.index')
            ->with('success', 'Jadwal bel berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail bel sekolah
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bel = \App\Models\BelSekolah::findOrFail($id);
        return view('admin.bel.show', compact('bel'));
    }

    /**
     * Menampilkan form untuk mengedit bel sekolah
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bel = \App\Models\BelSekolah::findOrFail($id);
        
        $daftarHari = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        
        $tipeBel = [
            'reguler' => 'Reguler',
            'istirahat' => 'Istirahat',
            'ujian' => 'Ujian',
            'khusus' => 'Khusus'
        ];
        
        $pilihanIkon = [
            'bell' => 'Bel',
            'coffee' => 'Istirahat',
            'book-open' => 'Belajar',
            'home' => 'Pulang',
            'user-clock' => 'Absensi',
            'flag-checkered' => 'Upacara',
            'book' => 'Ujian'
        ];
        
        return view('admin.bel.edit', compact('bel', 'daftarHari', 'tipeBel', 'pilihanIkon'));
    }

    /**
     * Mengupdate bel sekolah
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'waktu' => 'required',
            'tipe' => 'required|string',
            'hari' => 'nullable|string',
            'ikon' => 'required|string',
            'kode_warna' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);
        
        $bel = \App\Models\BelSekolah::findOrFail($id);
        
        // Handle file upload jika ada
        if ($request->hasFile('file_suara') && $request->file('file_suara')->isValid()) {
            // Hapus file lama jika ada
            if ($bel->file_suara && file_exists(public_path($bel->file_suara))) {
                unlink(public_path($bel->file_suara));
            }
            
            $file = $request->file('file_suara');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/suara'), $fileName);
            $bel->file_suara = 'uploads/suara/' . $fileName;
        }
        
        $bel->update([
            'nama' => $request->nama,
            'hari' => $request->hari,
            'waktu' => $request->waktu,
            'deskripsi' => $request->deskripsi,
            'aktif' => $request->has('aktif'),
            'tipe' => $request->tipe,
            'kode_warna' => $request->kode_warna ?? '#3B82F6',
            'ikon' => $request->ikon,
        ]);
        
        return redirect()->route('admin.bel.index')
            ->with('success', 'Jadwal bel berhasil diperbarui!');
    }

    /**
     * Menghapus bel sekolah
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bel = \App\Models\BelSekolah::findOrFail($id);
        
        // Hapus file suara jika ada
        if ($bel->file_suara && file_exists(public_path($bel->file_suara))) {
            unlink(public_path($bel->file_suara));
        }
        
        $bel->delete();
        
        return redirect()->route('admin.bel.index')
            ->with('success', 'Jadwal bel berhasil dihapus!');
    }
    
    /**
     * Mengaktifkan atau menonaktifkan bel
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleAktif($id)
    {
        $bel = \App\Models\BelSekolah::findOrFail($id);
        $bel->aktif = !$bel->aktif;
        $bel->save();
        
        return redirect()->back()
            ->with('success', 'Status bel berhasil diubah!');
    }
    
    /**
     * Menjalankan bel secara manual
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bunyikanBel($id)
    {
        $bel = \App\Models\BelSekolah::findOrFail($id);
        
        // Implementasi logic untuk membunyikan bel disini
        // Ini bisa berupa event broadcasting, atau logging
        
        // Contoh sementara
        \Log::info('Bel dibunyikan manual: ' . $bel->nama . ' pada ' . now()->format('H:i:s'));
        
        return redirect()->back()
            ->with('success', 'Bel "' . $bel->nama . '" berhasil dibunyikan!');
    }
    
    /**
     * Tampilkan bel untuk hari ini di dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function getBelHariIni()
    {
        $today = now()->format('l'); // Nama hari dalam bahasa Inggris (Monday, Tuesday, etc.)
        $bels = \App\Models\BelSekolah::aktif()
            ->where(function($query) use ($today) {
                $query->where('hari', $today)
                      ->orWhereNull('hari'); // Include bells for every day
            })
            ->orderBy('waktu')
            ->get();
            
        return response()->json($bels);
    }
    
    /**
     * AJAX: Mengaktifkan atau menonaktifkan bel
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxToggleAktif($id)
    {
        try {
            \Log::info('AJAX Toggle Aktif called for ID: ' . $id . ' by user: ' . auth()->user()->email);
            
            $bel = \App\Models\BelSekolah::findOrFail($id);
            \Log::info('Bell found: ' . $bel->nama . ', current status: ' . ($bel->aktif ? 'aktif' : 'tidak aktif'));
            
            $bel->aktif = !$bel->aktif;
            $bel->save();
            
            \Log::info('Bell status updated to: ' . ($bel->aktif ? 'aktif' : 'tidak aktif'));
            
            return response()->json([
                'success' => true,
                'message' => 'Status bel berhasil diubah!',
                'aktif' => $bel->aktif
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in ajaxToggleAktif: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status bel: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * AJAX: Menjalankan bel secara manual
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxBunyikanBel($id)
    {
        try {
            \Log::info('AJAX Bunyikan Bel called for ID: ' . $id . ' by user: ' . auth()->user()->email);
            
            $bel = \App\Models\BelSekolah::findOrFail($id);
            \Log::info('Bell found for ringing: ' . $bel->nama);
            
            // Cek apakah ada file suara
            $audioFile = null;
            if ($bel->file_suara && file_exists(public_path($bel->file_suara))) {
                $audioFile = asset($bel->file_suara);
                \Log::info('Audio file found: ' . $audioFile);
            } else {
                // Gunakan file suara default jika tidak ada, dengan prioritas enhanced bell
                $enhancedBellJs = 'sounds/enhanced-bell.js';
                $defaultAudio = 'sounds/default-bell.mp3';
                $basicBellJs = 'sounds/default-bell.js';
                
                if (file_exists(public_path($enhancedBellJs))) {
                    $audioFile = asset($enhancedBellJs);
                    \Log::info('Using enhanced bell JS generator: ' . $audioFile);
                } elseif (file_exists(public_path($defaultAudio))) {
                    $audioFile = asset($defaultAudio);
                    \Log::info('Using default audio: ' . $audioFile);
                } elseif (file_exists(public_path($basicBellJs))) {
                    $audioFile = asset($basicBellJs);
                    \Log::info('Using basic bell JS generator: ' . $audioFile);
                } else {
                    \Log::warning('No audio file found for bell: ' . $bel->nama . ' - client will use browser fallback');
                    $audioFile = null; // Let client handle all fallbacks
                }
            }
            
            // Log untuk keperluan monitoring
            \Log::info('Bel dibunyikan manual: ' . $bel->nama . ' pada ' . now()->format('H:i:s'));
            
            return response()->json([
                'success' => true,
                'message' => 'Bel "' . $bel->nama . '" berhasil dibunyikan!',
                'audio_file' => $audioFile,
                'bell_name' => $bel->nama,
                'bell_type' => $bel->tipe
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in ajaxBunyikanBel: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membunyikan bel: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * AJAX: Menghapus bel sekolah
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxDestroy($id)
    {
        try {
            $bel = \App\Models\BelSekolah::findOrFail($id);
            
            // Hapus file suara jika ada
            if ($bel->file_suara && file_exists(public_path($bel->file_suara))) {
                unlink(public_path($bel->file_suara));
            }
            
            $bel->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Jadwal bel berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus jadwal bel: ' . $e->getMessage()
            ], 500);
        }
    }
}
