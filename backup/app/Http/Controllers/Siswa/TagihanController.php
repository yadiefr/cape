<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{    public function __construct()
    {
        $this->middleware('auth:siswa');
    }    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('siswa.login')
                ->with('error', 'Silakan login terlebih dahulu');
        }
        
        // Get all bills (both global and student-specific)
        $tagihan = Tagihan::where(function($q) use ($siswa) {
            $q->whereNull('kelas_id')->whereNull('siswa_id')  // Global bills
              ->orWhere('siswa_id', $siswa->id)              // Student-specific bills
              ->orWhere('kelas_id', $siswa->kelas_id);       // Class-specific bills
        })->get();

        // Get student's payments
        $pembayaran = $siswa->pembayaran;

        // Prepare bill details: name, amount, total paid, remaining, status
        $detailTagihan = [];
        $totalTunggakan = 0;
        $totalTagihan = 0;
        $totalTelahDibayar = 0;

        foreach ($tagihan as $tag) {
            // Get all payments matching this bill
            $pembayaranTagihan = $pembayaran->where('tagihan_id', $tag->id);
            
            // Calculate total paid for this bill
            $totalDibayar = $pembayaranTagihan->sum('jumlah');
            
            // Calculate remaining amount
            $sisaTunggakan = $tag->nominal - $totalDibayar;
            
            // Determine status
            $status = $sisaTunggakan <= 0 ? 'Lunas' : 'Belum Lunas';

            // Add to total calculations
            $totalTagihan += $tag->nominal;
            $totalTelahDibayar += $totalDibayar;
            $totalTunggakan += max(0, $sisaTunggakan);

            // Store bill details
            $detailTagihan[] = [
                'id' => $tag->id,
                'nama' => $tag->nama_tagihan,
                'nominal' => $tag->nominal,
                'total_dibayar' => $totalDibayar,
                'sisa' => $sisaTunggakan,
                'status' => $status,
                'jenis' => $tag->siswa_id ? 'Personal' : ($tag->kelas_id ? 'Kelas' : 'Umum'),
                'tanggal_jatuh_tempo' => $tag->tanggal_jatuh_tempo,
                'keterangan' => $tag->keterangan,
                'pembayaran' => $pembayaranTagihan
            ];
        }

        return view('siswa.tagihan.index', compact(
            'detailTagihan',
            'totalTunggakan',
            'totalTagihan',
            'totalTelahDibayar'
        ));
    }    public function show($id)
    {
        $siswa = Auth::guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('siswa.login')
                ->with('error', 'Silakan login terlebih dahulu');
        }
        $tagihan = Tagihan::findOrFail($id);
        
        // Verify if student has access to this bill
        if (!is_null($tagihan->siswa_id) && $tagihan->siswa_id !== $siswa->id) {
            if (!is_null($tagihan->kelas_id) && $tagihan->kelas_id !== $siswa->kelas_id) {
                abort(403);
            }
        }

        // Get payments for this bill
        $pembayaran = Pembayaran::where('tagihan_id', $id)
                               ->where('siswa_id', $siswa->id)
                               ->orderBy('tanggal', 'desc')
                               ->get();

        // Calculate totals        $totalDibayar = $pembayaran->sum('jumlah');
        $sisaTunggakan = $tagihan->nominal - $totalDibayar;
        $status = $sisaTunggakan <= 0 ? 'Lunas' : 'Belum Lunas';

        return view('siswa.tagihan.show', compact(
            'tagihan',
            'pembayaran',
            'totalDibayar',
            'sisaTunggakan',
            'status'
        ));
    }
}
