<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PraktikKerjaLapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PraktikKerjaLapanganController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user()->siswa;
        $pkl = PraktikKerjaLapangan::where('siswa_id', $siswa->id)
                    ->latest()
                    ->get();
        
        return view('siswa.pkl.index', compact('pkl'));
    }

    public function show($id)
    {
        $siswa = Auth::guard('siswa')->user()->siswa;
        $pkl = PraktikKerjaLapangan::where('siswa_id', $siswa->id)
                    ->where('id', $id)
                    ->firstOrFail();
        
        return view('siswa.pkl.show', compact('pkl'));
    }

    public function uploadLaporan(Request $request, $id)
    {
        $request->validate([
            'dokumen_laporan' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $pkl = PraktikKerjaLapangan::where('siswa_id', Auth::guard('siswa')->user()->siswa->id)
                    ->where('id', $id)
                    ->firstOrFail();

        // Delete old file if exists
        if ($pkl->dokumen_laporan) {
            Storage::delete('public/pkl/laporan/' . $pkl->dokumen_laporan);
        }

        // Upload new file
        $filename = time() . '_' . $request->file('dokumen_laporan')->getClientOriginalName();
        $request->file('dokumen_laporan')->storeAs('public/pkl/laporan', $filename);
        
        $pkl->dokumen_laporan = $filename;
        $pkl->save();

        return back()->with('success', 'Laporan PKL berhasil diunggah');
    }

    public function downloadLaporan($id)
    {
        $pkl = PraktikKerjaLapangan::where('siswa_id', Auth::guard('siswa')->user()->siswa->id)
                    ->where('id', $id)
                    ->firstOrFail();

        if (!$pkl->dokumen_laporan) {
            return back()->with('error', 'Dokumen laporan tidak ditemukan');
        }

        return Storage::download('public/pkl/laporan/' . $pkl->dokumen_laporan);
    }

    public function downloadSurat($id) 
    {
        $pkl = PraktikKerjaLapangan::where('siswa_id', Auth::guard('siswa')->user()->siswa->id)
                    ->where('id', $id)
                    ->firstOrFail();

        if (!$pkl->surat_keterangan) {
            return back()->with('error', 'Surat keterangan tidak ditemukan');
        }

        return Storage::download('public/pkl/surat/' . $pkl->surat_keterangan);
    }
}
