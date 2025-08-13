<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{    
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        
        // Get all bills (both global and student-specific) with their payments
        $tagihan = Tagihan::where(function($q) use ($siswa) {
            $q->whereNull('kelas_id')->whereNull('siswa_id')  // Global bills
              ->orWhere('siswa_id', $siswa->id);             // Student-specific bills
        })
        ->with(['pembayaran' => function($query) use ($siswa) {
            $query->where('siswa_id', $siswa->id)
                  ->orderBy('tanggal', 'desc');
        }])
        ->get();

        $detailTagihan = [];
        $totalTunggakan = 0;
        $totalTagihan = 0;
        $totalTelahDibayar = 0;

        foreach ($tagihan as $tag) {
            // Calculate total paid for this bill
            $totalDibayar = $tag->pembayaran->sum('jumlah');
            
            // Calculate remaining amount
            $sisaTunggakan = $tag->nominal - $totalDibayar;
            
            // Determine status
            $status = $sisaTunggakan <= 0 ? 'Lunas' : 'Belum Lunas';

            // Add to total calculations
            $totalTagihan += $tag->nominal;
            $totalTelahDibayar += $totalDibayar;
            $totalTunggakan += max(0, $sisaTunggakan);

            // Store bill details with payments history
            $detailTagihan[] = [
                'id' => $tag->id,
                'nama' => $tag->nama_tagihan ?? $tag->nama,
                'nominal' => $tag->nominal,
                'total_dibayar' => $totalDibayar,
                'sisa' => $sisaTunggakan,
                'status' => $status,
                'pembayaran' => $tag->pembayaran
            ];
        }

        return view('siswa.pembayaran.index', compact(
            'detailTagihan',
            'totalTunggakan',
            'totalTagihan',
            'totalTelahDibayar'
        ));
    }    public function show($id)
    {
        $siswa = Auth::guard('siswa')->user();
        $tagihan = Tagihan::findOrFail($id);
        
        // Verify if student has access to this bill
        if (!is_null($tagihan->siswa_id) && $tagihan->siswa_id !== $siswa->id) {
            abort(403);
        }

        // Get payments for this bill
        $pembayaran = Pembayaran::where('tagihan_id', $id)
                               ->where('siswa_id', $siswa->id)
                               ->orderBy('tanggal', 'desc')
                               ->get();

        // Calculate totals
        $totalDibayar = $pembayaran->sum('jumlah');
        $sisaTunggakan = $tagihan->nominal - $totalDibayar;

        return view('siswa.pembayaran.show', compact(
            'tagihan',
            'pembayaran',
            'totalDibayar',
            'sisaTunggakan'
        ));
    }
}
