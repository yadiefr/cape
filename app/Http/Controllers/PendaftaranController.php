<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Pendaftaran;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pendaftar')->except(['landing', 'check', 'checkStatus', 'success']);
    }

    public function landing()
    {
        // Redirect to dashboard if already logged in
        if (Auth::guard('pendaftar')->check()) {
            return redirect()->route('pendaftar.dashboard');
        }

        // Cek apakah PPDB sedang dibuka
        $is_ppdb_open = Settings::getValue('is_ppdb_open', 'false') === 'true';
        $ppdb_start_date = Settings::getValue('ppdb_start_date');
        $ppdb_end_date = Settings::getValue('ppdb_end_date');
        $ppdb_year = Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1));
        
        // Jika PPDB ditutup, tampilkan pesan
        if (!$is_ppdb_open) {            return view('ppdb.closed');
        }

        return view('ppdb.landing', compact('ppdb_year', 'ppdb_start_date', 'ppdb_end_date'));
    }

    public function index()
    {
        // Cek apakah PPDB sedang dibuka
        $is_ppdb_open = Settings::getValue('is_ppdb_open', 'false') === 'true';
        $ppdb_start_date = Settings::getValue('ppdb_start_date');
        $ppdb_end_date = Settings::getValue('ppdb_end_date');
        $ppdb_year = Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1));
        
        // Jika PPDB ditutup, tampilkan pesan
        if (!$is_ppdb_open) {            return view('ppdb.closed');
        }

        // If already registered, redirect to dashboard
        if (Auth::guard('pendaftar')->user()->pendaftaran) {
            return redirect()->route('pendaftar.dashboard');
        }
        
        // Ambil daftar jurusan untuk dropdown
        $jurusan = Jurusan::all();
        
        return view('ppdb.form', compact('jurusan', 'ppdb_year', 'ppdb_start_date', 'ppdb_end_date'));
    }

    public function showForm()
    {
        return $this->index();
    }

    public function store(Request $request)
    {
        // Validasi data pendaftaran
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',            
            'nisn' => [
                'nullable',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $exists = Pendaftaran::whereRaw('LOWER(nisn) = ?', [strtolower($value)])->exists();
                        if ($exists) {
                            $fail('NISN ini sudah terdaftar.');
                        }
                    }
                },
            ],
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'asal_sekolah' => 'nullable|string|max:255',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'telepon_orangtua' => 'nullable|string|max:20',
            'alamat_orangtua' => 'nullable|string',
            'pilihan_jurusan_1' => 'nullable|exists:jurusan,id',
            'nilai_matematika' => 'nullable|numeric|min:0|max:100',
            'nilai_indonesia' => 'nullable|numeric|min:0|max:100',
            'nilai_inggris' => 'nullable|numeric|min:0|max:100',
            'dokumen_ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_skhun' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'dokumen_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_ktp_ortu' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Start the transaction
        DB::beginTransaction();
        
        try {
            $user = Auth::guard('pendaftar')->user();
            if (!$user) {
                throw new \Exception('User tidak ditemukan');
            }
        
            // Generate nomor pendaftaran
            $nomor_pendaftaran = Pendaftaran::generateNomorPendaftaran();
            \Log::info('Nomor pendaftaran generated: ' . $nomor_pendaftaran);
            
            // Upload dokumen
            $dokumen_paths = [];
            $dokumen_fields = ['dokumen_ijazah', 'dokumen_skhun', 'dokumen_foto', 'dokumen_kk', 'dokumen_ktp_ortu'];
            
            foreach ($dokumen_fields as $field) {
                if ($request->hasFile($field)) {
                    try {
                        $dokumen_paths[$field] = $request->file($field)->store('pendaftaran/'.$nomor_pendaftaran);
                        \Log::info('File uploaded: ' . $field . ' to ' . $dokumen_paths[$field]);
                    } catch (\Exception $e) {
                        \Log::error('File upload error for ' . $field . ': ' . $e->getMessage());
                        throw $e;
                    }
                } else {
                    $dokumen_paths[$field] = null;
                }
            }

            // Get validated data
            $nama_lengkap = $request->nama_lengkap;
            $jenis_kelamin = $request->jenis_kelamin;
            $nisn = $request->nisn;
            $tempat_lahir = $request->tempat_lahir;
            $tanggal_lahir = $request->tanggal_lahir;
            $agama = $request->agama;
            $alamat = $request->alamat;
            $telepon = $request->telepon;
            $asal_sekolah = $request->asal_sekolah;
            $nama_ayah = $request->nama_ayah;
            $nama_ibu = $request->nama_ibu;
            
            // Get first jurusan if not selected
            if (empty($request->pilihan_jurusan_1)) {
                $jurusan = Jurusan::first();
                if (!$jurusan) {
                    throw new \Exception('Tidak ada jurusan yang tersedia');
                }
                $pilihan_jurusan_1 = $jurusan->id;
                \Log::info('Using default jurusan: ' . $jurusan->id);
            } else {
                $pilihan_jurusan_1 = $request->pilihan_jurusan_1;
            }
            
            // Simpan data ke database
            $pendaftaran = new Pendaftaran();
            $pendaftaran->nomor_pendaftaran = $nomor_pendaftaran;
            $pendaftaran->nama_lengkap = $nama_lengkap;
            $pendaftaran->jenis_kelamin = $jenis_kelamin;
            $pendaftaran->nisn = $nisn;
            $pendaftaran->tempat_lahir = $tempat_lahir;
            $pendaftaran->tanggal_lahir = $tanggal_lahir;
            $pendaftaran->agama = $agama;
            $pendaftaran->alamat = $alamat;
            $pendaftaran->telepon = $telepon;
            $pendaftaran->email = $user->email;
            $pendaftaran->asal_sekolah = $asal_sekolah;
            $pendaftaran->nama_ayah = $nama_ayah;
            $pendaftaran->nama_ibu = $nama_ibu;
            $pendaftaran->pekerjaan_ayah = $request->pekerjaan_ayah;
            $pendaftaran->pekerjaan_ibu = $request->pekerjaan_ibu;
            $pendaftaran->telepon_orangtua = $request->telepon_orangtua;
            $pendaftaran->alamat_orangtua = $request->alamat_orangtua;
            $pendaftaran->pilihan_jurusan_1 = $pilihan_jurusan_1;
            $pendaftaran->nilai_matematika = $request->nilai_matematika;
            $pendaftaran->nilai_indonesia = $request->nilai_indonesia;
            $pendaftaran->nilai_inggris = $request->nilai_inggris;
            $pendaftaran->dokumen_ijazah = $dokumen_paths['dokumen_ijazah'];
            $pendaftaran->dokumen_skhun = $dokumen_paths['dokumen_skhun'];
            $pendaftaran->dokumen_foto = $dokumen_paths['dokumen_foto'];
            $pendaftaran->dokumen_kk = $dokumen_paths['dokumen_kk'];
            $pendaftaran->dokumen_ktp_ortu = $dokumen_paths['dokumen_ktp_ortu'];
            $pendaftaran->tanggal_pendaftaran = now();
            $pendaftaran->tahun_ajaran = Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1));
            $pendaftaran->status = 'menunggu';
            $pendaftaran->user_id = $user->id;

            try {
                $pendaftaran->save();
                \Log::info('Pendaftaran saved successfully. ID: ' . $pendaftaran->id);
            } catch (\Exception $e) {
                \Log::error('Error saving pendaftaran: ' . $e->getMessage());
                throw $e;
            }
            
            DB::commit();
            \Log::info('Transaction committed successfully');

            return redirect()
                ->route('pendaftar.dashboard')
                ->with('success', 'Pendaftaran berhasil! Nomor pendaftaran Anda: ' . $nomor_pendaftaran);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in pendaftaran store: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Delete uploaded files if any
            foreach ($dokumen_paths as $path) {
                if ($path) {
                    try {
                        Storage::delete($path);
                        \Log::info('Deleted uploaded file: ' . $path);
                    } catch (\Exception $e) {
                        \Log::error('Error deleting file ' . $path . ': ' . $e->getMessage());
                    }
                }
            }
            
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function success($nomor_pendaftaran)
    {
        $pendaftaran = Pendaftaran::where('nomor_pendaftaran', $nomor_pendaftaran)->first();
        
        if (!$pendaftaran) {
            return redirect()->route('pendaftaran.index')->with('error', 'Nomor pendaftaran tidak ditemukan.');
        }
          return view('ppdb.success', compact('pendaftaran'));
    }
    
    public function check()
    {
        if (Auth::guard('pendaftar')->check()) {
            $pendaftaran = Auth::guard('pendaftar')->user()->pendaftaran;
            if ($pendaftaran) {
                return view('ppdb.result', compact('pendaftaran'));
            }
        }
        
        return view('ppdb.check');
    }
    
    public function checkStatus(Request $request)
    {
        $request->validate([
            'nomor_pendaftaran' => 'required|string|exists:pendaftaran,nomor_pendaftaran',
            'nisn' => 'required|string|exists:pendaftaran,nisn',
        ]);

        $pendaftaran = Pendaftaran::where('nomor_pendaftaran', $request->nomor_pendaftaran)
                                 ->where('nisn', $request->nisn)
                                 ->first();
        
        if (!$pendaftaran) {
            return back()->with('error', 'Data pendaftaran tidak ditemukan. Periksa kembali nomor pendaftaran dan NISN Anda.');
        }
        
        $nextStep = '';
        switch($pendaftaran->status) {
            case 'menunggu':
                $nextStep = 'Silahkan menunggu verifikasi dari pihak sekolah. Anda akan dihubungi untuk informasi selanjutnya.';
                break;
            case 'diterima':
                $nextStep = 'Selamat! Anda diterima. Silahkan melengkapi berkas pendaftaran dan melakukan daftar ulang sesuai jadwal yang ditentukan.';
                break;
            case 'ditolak':
                $nextStep = 'Mohon maaf, pendaftaran Anda tidak dapat kami terima. Silahkan hubungi pihak sekolah untuk informasi lebih lanjut.';
                break;
            case 'cadangan':
                $nextStep = 'Anda masuk dalam daftar cadangan. Silahkan menunggu informasi selanjutnya dari pihak sekolah.';
                break;
        }
          return view('ppdb.result', compact('pendaftaran', 'nextStep'));
    }
    
    public function status()
    {
        // Jika sudah login sebagai pendaftar
        if (Auth::guard('pendaftar')->check()) {
            $pendaftaran = Auth::guard('pendaftar')->user()->pendaftaran;
            if ($pendaftaran) {
                return redirect()->route('pendaftaran.check');
            }
        }
        
        return view('ppdb.check');
    }

    public function print($nomor, $nisn)
    {
        if (!Auth::guard('pendaftar')->check()) {
            return redirect()->route('pendaftar.login');
        }

        $pendaftaran = Pendaftaran::where('nomor_pendaftaran', $nomor)
                               ->where('nisn', $nisn)
                               ->where('user_id', Auth::guard('pendaftar')->id())
                               ->firstOrFail();
        
        if (!$pendaftaran) {
            return redirect()->route('pendaftaran.check')->with('error', 'Data pendaftaran tidak ditemukan.');
        }
        
        return view('ppdb.print', compact('pendaftaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        // Cek apakah PPDB sedang dibuka
        $is_ppdb_open = Settings::getValue('is_ppdb_open', 'false') === 'true';
        $ppdb_start_date = Settings::getValue('ppdb_start_date');
        $ppdb_end_date = Settings::getValue('ppdb_end_date');
        $ppdb_year = Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1));
        
        // Jika PPDB ditutup, tampilkan pesan
        if (!$is_ppdb_open) {            return redirect()->route('pendaftaran.check')
                ->with('error', 'Mohon maaf, pendaftaran PPDB sedang ditutup.');
        }

        // Ambil data pendaftaran user yang login
        $pendaftaran = Auth::guard('pendaftar')->user()->pendaftaran;
        if (!$pendaftaran) {
            return redirect()->route('pendaftaran.form')
                ->with('error', 'Anda belum melakukan pendaftaran.');
        }

        // Ambil daftar jurusan untuk dropdown
        $jurusan = Jurusan::all();
        
        return view('ppdb.edit', compact('pendaftaran', 'jurusan', 'ppdb_year', 'ppdb_start_date', 'ppdb_end_date'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validasi data pendaftaran
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',            
            'nisn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pendaftaran', 'nisn')->ignore(Auth::guard('pendaftar')->user()->pendaftaran->id)
            ],
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'telepon_orangtua' => 'nullable|string|max:20',
            'alamat_orangtua' => 'nullable|string',
            'pilihan_jurusan_1' => 'required|exists:jurusan,id',
            'nilai_matematika' => 'nullable|numeric|min:0|max:100',
            'nilai_indonesia' => 'nullable|numeric|min:0|max:100',
            'nilai_inggris' => 'nullable|numeric|min:0|max:100',
            'dokumen_ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_skhun' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', 
            'dokumen_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'dokumen_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_ktp_ortu' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Start the transaction
        DB::beginTransaction();
        
        try {
            $pendaftaran = Auth::guard('pendaftar')->user()->pendaftaran;
            if (!$pendaftaran) {
                throw new \Exception('Pendaftaran tidak ditemukan');
            }

            // Upload dokumen jika ada
            $dokumen_fields = ['dokumen_ijazah', 'dokumen_skhun', 'dokumen_foto', 'dokumen_kk', 'dokumen_ktp_ortu'];
            foreach ($dokumen_fields as $field) {
                if ($request->hasFile($field)) {
                    // Hapus file lama jika ada
                    if ($pendaftaran->$field) {
                        Storage::delete($pendaftaran->$field);
                    }
                    // Upload file baru
                    $pendaftaran->$field = $request->file($field)->store('pendaftaran/'.$pendaftaran->nomor_pendaftaran);
                }
            }

            // Update data pendaftaran
            $pendaftaran->nama_lengkap = $request->nama_lengkap;
            $pendaftaran->jenis_kelamin = $request->jenis_kelamin;
            $pendaftaran->nisn = $request->nisn;
            $pendaftaran->tempat_lahir = $request->tempat_lahir;
            $pendaftaran->tanggal_lahir = $request->tanggal_lahir;
            $pendaftaran->agama = $request->agama;
            $pendaftaran->alamat = $request->alamat;
            $pendaftaran->telepon = $request->telepon;
            $pendaftaran->email = $request->email;
            $pendaftaran->asal_sekolah = $request->asal_sekolah;
            $pendaftaran->nama_ayah = $request->nama_ayah;
            $pendaftaran->nama_ibu = $request->nama_ibu;
            $pendaftaran->pekerjaan_ayah = $request->pekerjaan_ayah;
            $pendaftaran->pekerjaan_ibu = $request->pekerjaan_ibu;
            $pendaftaran->telepon_orangtua = $request->telepon_orangtua;
            $pendaftaran->alamat_orangtua = $request->alamat_orangtua;
            $pendaftaran->pilihan_jurusan_1 = $request->pilihan_jurusan_1;
            $pendaftaran->nilai_matematika = $request->nilai_matematika;
            $pendaftaran->nilai_indonesia = $request->nilai_indonesia;
            $pendaftaran->nilai_inggris = $request->nilai_inggris;
            $pendaftaran->save();
            
            DB::commit();
            \Log::info('Pendaftaran updated successfully. ID: ' . $pendaftaran->id);

            return redirect()
                ->route('pendaftar.dashboard')
                ->with('success', 'Data pendaftaran berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in pendaftaran update: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function dashboard()
    {
        // Get the logged in user's registration data
        $pendaftaran = Auth::guard('pendaftar')->user()->pendaftaran;
        
        // If no registration exists, redirect to registration form
        if (!$pendaftaran) {
            return redirect()->route('pendaftaran.form')
                ->with('error', 'Anda belum melakukan pendaftaran.');
        }
        
        // Get PPDB period info
        $ppdb_year = Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1));
        
        // Get next steps based on status
        $nextStep = '';
        switch($pendaftaran->status) {
            case 'menunggu':
                $nextStep = 'Silahkan menunggu verifikasi dari pihak sekolah. Anda akan dihubungi untuk informasi selanjutnya.';
                break;
            case 'diterima':
                $nextStep = 'Selamat! Anda diterima. Silahkan melengkapi berkas pendaftaran dan melakukan daftar ulang sesuai jadwal yang ditentukan.';
                break;
            case 'ditolak':
                $nextStep = 'Mohon maaf, pendaftaran Anda tidak dapat kami terima. Silahkan hubungi pihak sekolah untuk informasi lebih lanjut.';
                break;
            case 'cadangan':
                $nextStep = 'Anda masuk dalam daftar cadangan. Silahkan menunggu informasi selanjutnya dari pihak sekolah.';
                break;
        }
        
        return view('ppdb.dashboard', compact('pendaftaran', 'nextStep', 'ppdb_year'));
    }
}
