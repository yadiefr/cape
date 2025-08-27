<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingsJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class JadwalSettingsController extends Controller
{
    public function index()
    {
        try {
            $settings = SettingsJadwal::orderBy('hari')->get();
            // dd($settings);
            Log::info('Settings data:', ['settings' => $settings->toArray()]);
            return view('admin.settings.jadwal.index', compact('settings'));
        } catch (\Exception $e) {
            Log::error('Error in index method:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data pengaturan jadwal.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
                'jam_mulai' => 'required|date_format:H:i',
                'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
                'jam_istirahat_mulai' => 'nullable|date_format:H:i',
                'jam_istirahat_selesai' => 'nullable|date_format:H:i|after:jam_istirahat_mulai',
                'jam_istirahat2_mulai' => 'nullable|date_format:H:i',
                'jam_istirahat2_selesai' => 'nullable|date_format:H:i|after:jam_istirahat2_mulai',
                'jumlah_jam_pelajaran' => 'required|integer|min:1|max:12',
                'durasi_per_jam' => 'required|integer|min:30|max:120',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $request->all();
            $data['is_active'] = true;
            $data['jam_istirahat2_mulai'] = $request->jam_istirahat2_mulai;
            $data['jam_istirahat2_selesai'] = $request->jam_istirahat2_selesai;

            Log::info('Creating new schedule setting:', ['data' => $data]);
            $setting = SettingsJadwal::create($data);
            Log::info('Schedule setting created:', ['setting' => $setting->toArray()]);

            return redirect()->route('admin.settings.jadwal.index')
                ->with('success', 'Pengaturan jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error in store method:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan pengaturan jadwal.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $setting = SettingsJadwal::findOrFail($id);
            Log::info('Editing schedule setting:', ['setting' => $setting->toArray()]);
            return view('admin.settings.jadwal.edit', compact('setting'));
        } catch (\Exception $e) {
            Log::error('Error in edit method:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data pengaturan jadwal.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
                'jam_mulai' => 'required|date_format:H:i',
                'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
                'jam_istirahat_mulai' => 'nullable|date_format:H:i',
                'jam_istirahat_selesai' => 'nullable|date_format:H:i|after:jam_istirahat_mulai',
                'jam_istirahat2_mulai' => 'nullable|date_format:H:i',
                'jam_istirahat2_selesai' => 'nullable|date_format:H:i|after:jam_istirahat2_mulai',
                'jumlah_jam_pelajaran' => 'required|integer|min:1|max:12',
                'durasi_per_jam' => 'required|integer|min:30|max:120',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $setting = SettingsJadwal::findOrFail($id);
            $data = $request->all();
            $data['is_active'] = $request->has('is_active');
            $data['jam_istirahat2_mulai'] = $request->jam_istirahat2_mulai;
            $data['jam_istirahat2_selesai'] = $request->jam_istirahat2_selesai;

            Log::info('Updating schedule setting:', ['id' => $id, 'data' => $data]);
            $setting->update($data);
            Log::info('Schedule setting updated:', ['setting' => $setting->toArray()]);

            return redirect()->route('admin.settings.jadwal.index')
                ->with('success', 'Pengaturan jadwal berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error in update method:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui pengaturan jadwal.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $setting = SettingsJadwal::findOrFail($id);
            Log::info('Deleting schedule setting:', ['setting' => $setting->toArray()]);
            $setting->delete();

            return redirect()->route('admin.settings.jadwal.index')
                ->with('success', 'Pengaturan jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error in destroy method:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus pengaturan jadwal.');
        }
    }

    public function toggleStatus($id)
    {
        try {
            $setting = SettingsJadwal::findOrFail($id);
            $setting->is_active = !$setting->is_active;
            $setting->save();
            
            return redirect()->back()->with('success', 'Status pengaturan jadwal berhasil diubah');
        } catch (\Exception $e) {
            Log::error('Error in toggleStatus method:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah status pengaturan jadwal.');
        }
    }

    /**
     * Get settings as JSON for table view
     * 
     * @return JsonResponse
     */
    public function getSettings(): JsonResponse
    {
        try {
            $settings = SettingsJadwal::where('is_active', true)
                ->orderBy('hari')
                ->get();
                
            return response()->json([
                'success' => true,
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getSettings method:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengaturan jadwal.'
            ], 500);
        }
    }
    
    /**
     * Store multiple settings at once
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function batchStore(Request $request): JsonResponse
    {
        try {
            $settings = $request->input('settings', []);
            
            if (empty($settings)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pengaturan jadwal yang diberikan.'
                ]);
            }
            
            $createdSettings = [];
            
            foreach ($settings as $setting) {
                // Check if setting already exists for this day
                $exists = SettingsJadwal::where('hari', $setting['hari'])->exists();
                
                if (!$exists) {
                    $newSetting = SettingsJadwal::create([
                        'hari' => $setting['hari'],
                        'jam_mulai' => $setting['jam_mulai'],
                        'jam_selesai' => $setting['jam_selesai'],
                        'jam_istirahat_mulai' => $setting['jam_istirahat_mulai'],
                        'jam_istirahat_selesai' => $setting['jam_istirahat_selesai'],
                        'jam_istirahat2_mulai' => $setting['jam_istirahat2_mulai'],
                        'jam_istirahat2_selesai' => $setting['jam_istirahat2_selesai'],
                        'jumlah_jam_pelajaran' => $setting['jumlah_jam_pelajaran'],
                        'durasi_per_jam' => $setting['durasi_per_jam'],
                        'is_active' => $setting['is_active'] ?? true
                    ]);
                    
                    $createdSettings[] = $newSetting;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => count($createdSettings) . ' pengaturan jadwal berhasil ditambahkan.',
                'settings' => $createdSettings
            ]);
        } catch (\Exception $e) {
            Log::error('Error in batchStore method:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pengaturan jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
}
