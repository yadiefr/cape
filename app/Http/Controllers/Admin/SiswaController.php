<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::query()
            ->with(['kelas', 'jurusan']);
        
        // Search by name, NIS, or NISN
        if ($request->has('search') ) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('siswa.nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('siswa.nis', 'like', "%{$search}%")
                  ->orWhere('siswa.nisn', 'like', "%{$search}%");
            });
        }
        
        // Filter by class
        if ($request->has('kelas_id') && $request->kelas_id) {
            $query->where('siswa.kelas_id', $request->kelas_id);
        }
        
        // Filter by major
        if ($request->has('jurusan_id') && $request->jurusan_id) {
            $query->where('siswa.jurusan_id', $request->jurusan_id);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('siswa.status', $request->status);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'nama_lengkap');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Validasi kolom yang bisa di-sort
        $allowedSortColumns = ['siswa.nama_lengkap', 'kelas.nama_kelas'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'siswa.nama_lengkap';
        }
        
        // Validasi order
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }
        
        // Apply sorting
        if ($sortBy === 'kelas.nama_kelas') {
            $query->leftJoin('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                  ->orderBy('kelas.nama_kelas', $sortOrder)
                  ->select('siswa.*');
        } else {
            $query->leftJoin('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                  ->orderBy($sortBy, $sortOrder)
                  ->select('siswa.*');
        }

        $siswa = $query->get(); // Changed from paginate(15) to get() to show all data
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();
        
        // Data untuk sorting
        $currentSort = $request->get('sort_by', 'siswa.nama_lengkap');
        $currentOrder = $request->get('sort_order', 'asc');
        
        return view('admin.siswa.index', compact('siswa', 'kelas', 'jurusan', 'currentSort', 'currentOrder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();
        
        return view('admin.siswa.create', compact('kelas', 'jurusan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request with custom messages
        $messages = [
            'nis.unique' => 'NIS sudah digunakan oleh siswa lain.',
            'nisn.unique' => 'NISN sudah digunakan oleh siswa lain.',
            'email.unique' => 'Email sudah digunakan oleh siswa lain.',
            'email.email' => 'Format email tidak valid.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'foto.image' => 'File harus berupa gambar (jpeg, png, jpg, gif).',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak tersedia.',
            'jurusan_id.exists' => 'Jurusan yang dipilih tidak tersedia.',
            'jenis_kelamin.in' => 'Jenis kelamin harus dipilih (Laki-laki atau Perempuan).',
            'tahun_masuk.digits' => 'Tahun masuk harus berupa 4 digit angka.',
        ];
        
        $validated = $request->validate([
            'nis' => 'nullable|string|max:20|unique:siswa',
            'nisn' => 'nullable|string|max:20|unique:siswa',
            'nama_lengkap' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:siswa',
            'password' => 'nullable|min:8|confirmed',
            'kelas_id' => 'nullable|exists:kelas,id',
            'jurusan_id' => 'nullable|exists:jurusan,id',
            'tahun_masuk' => 'nullable|digits:4',
            'status' => 'nullable|in:aktif,tidak_aktif,lulus,pindah',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'no_telp_ayah' => 'nullable|string|max:15',
            'no_telp_ibu' => 'nullable|string|max:15',
            'alamat_orangtua' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);
        
        DB::beginTransaction();
        
        try {
            // Upload foto jika ada
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('siswa', 'public');
            }

            // Handle password - selalu gunakan tanggal lahir jika tersedia
            if (!empty($validated['tanggal_lahir'])) {
                // Convert YYYY-MM-DD to DDMMYYYY
                $birthDate = new \DateTime($validated['tanggal_lahir']);
                $password = $birthDate->format('dmY');
            } else {
                // Fallback to default password if no birth date
                $password = '12345678';
            }

            // Create siswa record - password will be hashed by the model mutator
            $siswa = Siswa::create([
                'nis' => $validated['nis'] ?? null,
                'nisn' => $validated['nisn'] ?? null,
                'nama_lengkap' => $validated['nama_lengkap'] ?? ($validated['nis'] ?? 'Siswa Baru'),
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'agama' => $validated['agama'] ?? null,
                'alamat' => $validated['alamat'] ?? '',
                'telepon' => $validated['telepon'] ?? null,
                'email' => $validated['email'] ?? null,
                'password' => $password,
                'kelas_id' => $validated['kelas_id'] ?? null,
                'jurusan_id' => $validated['jurusan_id'] ?? null,
                'tahun_masuk' => $validated['tahun_masuk'] ?? date('Y'),
                'status' => $validated['status'] ?? 'aktif',
                'nama_ayah' => $validated['nama_ayah'] ?? null,
                'nama_ibu' => $validated['nama_ibu'] ?? null,
                'pekerjaan_ayah' => $validated['pekerjaan_ayah'] ?? null,
                'pekerjaan_ibu' => $validated['pekerjaan_ibu'] ?? null,
                'no_telp_ayah' => $validated['no_telp_ayah'] ?? null,
                'no_telp_ibu' => $validated['no_telp_ibu'] ?? null,
                'alamat_orangtua' => $validated['alamat_orangtua'] ?? null,
                'foto' => $fotoPath,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil ditambahkan! Username siswa adalah NIS.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete foto jika ada
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            
            // Check for specific errors and provide more user-friendly messages
            $errorMessage = $this->getFormattedErrorMessage($e);
            
            return redirect()->back()->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas', 'jurusan', 'nilai', 'absensi', 'pembayaran']);
        // Ambil tagihan yang berlaku untuk siswa ini (global, kelas, atau spesifik)
        $tagihanList = \App\Models\Tagihan::where(function($q) use ($siswa) {
            $q->whereNull('kelas_id')->whereNull('siswa_id')  // Tagihan global (semua siswa)
              ->orWhere('kelas_id', $siswa->kelas_id)         // Tagihan untuk kelas siswa ini
              ->orWhere('siswa_id', $siswa->id);              // Tagihan spesifik siswa
        })->get();

        // Ambil pembayaran siswa
        $pembayaranSiswa = $siswa->pembayaran;

        // Siapkan detail tagihan: nama, nominal, total dibayar, sisa, status
        $detailTagihan = [];
        $totalTunggakan = 0;
        $totalTagihan = 0;
        $totalTelahDibayar = 0;
        
        foreach ($tagihanList as $tagihan) {
            // Ambil semua pembayaran untuk tagihan ini berdasarkan tagihan_id
            $pembayaranTagihan = $pembayaranSiswa->where('tagihan_id', $tagihan->id);
            $totalDibayar = $pembayaranTagihan->sum('jumlah');  // Hitung semua pembayaran tanpa filter status
            $sisa = max(0, $tagihan->nominal - $totalDibayar);
            
            // Tambahkan ke total
            $totalTunggakan += $sisa;
            $totalTagihan += $tagihan->nominal;
            $totalTelahDibayar += $totalDibayar;
            
            // Status: Lunas, Sebagian, Belum Bayar
            if ($totalDibayar >= $tagihan->nominal && $tagihan->nominal > 0) {
                $status = 'Lunas';
            } elseif ($totalDibayar > 0 && $totalDibayar < $tagihan->nominal) {
                $status = 'Sebagian';
            } else {
                $status = 'Belum Bayar';
            }
            $detailTagihan[] = [
                'nama_tagihan' => $tagihan->nama_tagihan,
                'nominal' => $tagihan->nominal,
                'total_dibayar' => $totalDibayar,
                'sisa' => $sisa,
                'status' => $status,
            ];
        }

        // Set tunggakan dan status keuangan pada siswa object
        $siswa->tunggakan = $totalTunggakan;
        $siswa->total_tagihan = $totalTagihan;
        $siswa->total_telah_dibayar = $totalTelahDibayar;
        $siswa->status_keuangan = $totalTunggakan <= 0 ? 'Lunas' : 'Belum Lunas';

        return view('admin.siswa.show', compact('siswa', 'detailTagihan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        try {
            // Load relationships with complete data
            $siswa->load(['kelas', 'jurusan', 'nilai', 'absensi', 'pembayaran']);
            
            $kelas = Kelas::all();
            $jurusan = Jurusan::all();
            
            // Debug info
            \Log::info('Edit Siswa: ', [
                'siswa_id' => $siswa->id,
                'jurusan_count' => $jurusan->count(),
                'kelas_count' => $kelas->count()
            ]);
            
            return view('admin.siswa.edit', compact('siswa', 'kelas', 'jurusan'));
        } catch (\Exception $e) {
            \Log::error('Error in SiswaController::edit: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        // Validate the request with custom messages
        $messages = [
            'nis.unique' => 'NIS sudah digunakan oleh siswa lain.',
            'nisn.unique' => 'NISN sudah digunakan oleh siswa lain.',
            'email.unique' => 'Email sudah digunakan oleh siswa lain.',
            'email.email' => 'Format email tidak valid.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'foto.image' => 'File harus berupa gambar (jpeg, png, jpg, gif).',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak tersedia.',
            'jurusan_id.exists' => 'Jurusan yang dipilih tidak tersedia.',
            'jenis_kelamin.in' => 'Jenis kelamin harus dipilih (Laki-laki atau Perempuan).',
            'tahun_masuk.digits' => 'Tahun masuk harus berupa 4 digit angka.',
        ];
        
        $validated = $request->validate([
            'nis' => ['nullable', 'string', 'max:20', Rule::unique('siswa')->ignore($siswa->id)],
            'nisn' => ['nullable', 'string', 'max:20', Rule::unique('siswa')->ignore($siswa->id)],
            'nama_lengkap' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:15',
            'email' => ['nullable', 'email', Rule::unique('siswa')->ignore($siswa->id)],
            'kelas_id' => 'nullable|exists:kelas,id',
            'jurusan_id' => 'nullable|exists:jurusan,id',
            'tahun_masuk' => 'nullable|digits:4',
            'status' => 'nullable|in:aktif,tidak_aktif,lulus,pindah',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'no_telp_ayah' => 'nullable|string|max:15',
            'no_telp_ibu' => 'nullable|string|max:15',
            'alamat_orangtua' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ], $messages);
        
        DB::beginTransaction();
        
        try {
            // Upload foto jika ada
            $fotoPath = $siswa->foto;
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                    Storage::disk('public')->delete($fotoPath);
                }
                $fotoPath = $request->file('foto')->store('siswa', 'public');
            }
            
            // Handle password reset if checkbox is checked
            if ($request->has('reset_default') && $request->reset_default) {
                if (!empty($validated['tanggal_lahir'])) {
                    // Convert YYYY-MM-DD to DDMMYYYY
                    $birthDate = new \DateTime($validated['tanggal_lahir']);
                    $password = $birthDate->format('dmY');
                } else {
                    // Fallback to default password if no birth date
                    $password = '12345678';
                }
                $validated['password'] = $password;
            }

            // Update siswa record
            $siswa->update(array_merge(
                [
                    'nis' => $validated['nis'],
                    'nisn' => $validated['nisn'],
                    'nama_lengkap' => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'agama' => $validated['agama'],
                'alamat' => $validated['alamat'],
                'telepon' => $validated['telepon'],
                'email' => $validated['email'],
                'kelas_id' => $validated['kelas_id'],
                'jurusan_id' => $validated['jurusan_id'],
                'tahun_masuk' => $validated['tahun_masuk'],
                'status' => $validated['status'],
                'nama_ayah' => $validated['nama_ayah'],
                'nama_ibu' => $validated['nama_ibu'],
                'pekerjaan_ayah' => $validated['pekerjaan_ayah'],
                'pekerjaan_ibu' => $validated['pekerjaan_ibu'],
                'no_telp_ayah' => $validated['no_telp_ayah'],
                'no_telp_ibu' => $validated['no_telp_ibu'],
                'alamat_orangtua' => $validated['alamat_orangtua'],
                'foto' => $fotoPath,
            ], 
                isset($validated['password']) ? ['password' => $validated['password']] : []
            ));
            
            DB::commit();
            
            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Get formatted error message
            $errorMessage = $this->getFormattedErrorMessage($e);
            
            return redirect()->back()->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa, Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Hapus foto jika ada
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }
            
            $namaLengkap = $siswa->nama_lengkap;
            
            // Hapus siswa record
            $siswa->delete();
            
            DB::commit();
            
            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data siswa {$namaLengkap} berhasil dihapus!"
                ]);
            }
            
            return redirect()->route('admin.siswa.index')
                ->with('success', "Data siswa {$namaLengkap} berhasil dihapus!");
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data siswa: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus data siswa: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        // Debug: Test if method is being called
        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'File tidak ditemukan dalam request');
        }
        
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,tsv,txt',
        ]);

        try {
            $imported = 0;
            $updated = 0;
            $errors = [];
            $file = $request->file('file');
            
            \Log::info('Starting import process', [
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_extension' => $file->getClientOriginalExtension()
            ]);
            
            // Handle different file types
            $extension = $file->getClientOriginalExtension();
            
            if (in_array($extension, ['tsv', 'txt'])) {
                // Handle TSV files
                $content = file_get_contents($file->getRealPath());
                $lines = explode("\n", $content);
                $data = [];
                foreach ($lines as $line) {
                    if (trim($line)) {
                        $data[] = explode("\t", $line);
                    }
                }
                $rows = $data;
            } else {
                // Handle Excel/CSV files
                $data = Excel::toArray([], $file);
                $rows = $data[0];
            }
            
            \Log::info('File processed, rows count: ' . count($rows));
            
            // Log first few rows to debug
            foreach (array_slice($rows, 0, 3) as $index => $row) {
                \Log::info("Row $index preview", ['data' => array_slice($row, 0, 5)]);
            }
            
            foreach ($rows as $i => $row) {
                \Log::info("Processing row $i", ['row_data' => $row]);
                
                // Skip header row - SELALU skip baris pertama (index 0)
                if ($i === 0) {
                    \Log::info("Skipping header row $i (always skip first row)");
                    continue;
                }
                
                // Clean and validate row data
                $row = array_map(function($item) {
                    return $item !== null ? trim($item) : '';
                }, $row);
                
                // Filter out empty rows
                if (empty($row[0]) && empty($row[1]) && empty($row[2])) {
                    \Log::info("Skipping empty row $i");
                    continue;
                }
                
                // Check if this might be a header row (contains non-numeric NIS)
                if (!empty($row[0]) && !is_numeric($row[0]) && 
                    in_array(strtolower(trim($row[0])), ['nis', 'nisn', 'nama', 'nama_lengkap', 'id', 'no'])) {
                    \Log::info("Skipping potential header row $i", ['first_column' => $row[0]]);
                    continue;
                }
                
                // Check if row has minimum required columns (NIS, NISN, Nama)
                if (count($row) < 3 || empty($row[0]) || empty($row[1]) || empty($row[2])) {
                    $error = "Baris " . ($i + 1) . ": Data tidak lengkap (minimal perlu NIS, NISN, dan Nama)";
                    $errors[] = $error;
                    \Log::warning($error, ['row' => $row]);
                    continue;
                }
                
                try {
                    // Check if siswa already exists
                    $existing = Siswa::where('nis', $row[0])
                                   ->orWhere('nisn', $row[1])
                                   ->first();
                    
                    \Log::info("Checking existing siswa", [
                        'nis' => $row[0],
                        'nisn' => $row[1],
                        'existing_found' => $existing ? true : false
                    ]);
                    
                    // Parse tanggal lahir dengan handling yang benar - support multiple formats
                    $tanggal_lahir = null; // default null untuk empty dates
                    \Log::info("Parsing tanggal lahir for row " . ($i + 1), ['raw_date' => $row[5] ?? 'NOT_SET']);
                    
                    if (isset($row[5]) && !empty(trim($row[5]))) {
                        try {
                            $dateString = trim($row[5]);
                            \Log::info("Date string found", ['date_string' => $dateString]);
                            
                            // Try different date formats
                            if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $dateString)) {
                                // DD-MM-YYYY format
                                $tanggal_lahir = \Carbon\Carbon::createFromFormat('d-m-Y', $dateString)->format('Y-m-d');
                                \Log::info("Parsed DD-MM-YYYY format", ['result' => $tanggal_lahir]);
                            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
                                // YYYY-MM-DD format
                                $tanggal_lahir = \Carbon\Carbon::parse($dateString)->format('Y-m-d');
                                \Log::info("Parsed YYYY-MM-DD format", ['result' => $tanggal_lahir]);
                            } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateString)) {
                                // DD/MM/YYYY format
                                $tanggal_lahir = \Carbon\Carbon::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
                                \Log::info("Parsed DD/MM/YYYY format", ['result' => $tanggal_lahir]);
                            } else {
                                // Try to parse with Carbon (fallback)
                                $tanggal_lahir = \Carbon\Carbon::parse($dateString)->format('Y-m-d');
                                \Log::info("Parsed with Carbon fallback", ['result' => $tanggal_lahir]);
                            }
                        } catch (\Exception $e) {
                            \Log::warning("Invalid date format for row " . ($i + 1), ['date' => $row[5], 'error' => $e->getMessage()]);
                            $tanggal_lahir = null; // Set to null if invalid
                        }
                    } else {
                        \Log::info("Date field is empty, setting to null", ['row' => $i + 1]);
                    }
                    
                    // Generate password: jika ada tanggal lahir gunakan DDMMYYYY, jika tidak gunakan 'password'
                    $password = 'password'; // default password
                    if ($tanggal_lahir && $tanggal_lahir !== '2000-01-01') {
                        try {
                            $dateObj = \Carbon\Carbon::parse($tanggal_lahir);
                            $password = $dateObj->format('dmY'); // Format: DDMMYYYY
                            \Log::info("Password generated from date", ['tanggal_lahir' => $tanggal_lahir, 'password' => $password]);
                        } catch (\Exception $e) {
                            $password = 'password'; // Fallback to default
                            \Log::info("Password fallback due to date error", ['error' => $e->getMessage()]);
                        }
                    } else {
                        \Log::info("Using default password", ['tanggal_lahir' => $tanggal_lahir, 'password' => $password]);
                    }
                    
                    // Prepare data for creation/update dengan nullable fields
                    $data = [
                        'nis' => $row[0],
                        'nisn' => $row[1],
                        'nama_lengkap' => $row[2],
                        'jenis_kelamin' => (isset($row[3]) && !empty(trim($row[3]))) ? trim($row[3]) : 'L',
                        'tempat_lahir' => (isset($row[4]) && !empty(trim($row[4]))) ? trim($row[4]) : null,
                        'tanggal_lahir' => $tanggal_lahir,
                        'agama' => (isset($row[6]) && !empty(trim($row[6]))) ? trim($row[6]) : null,
                        'alamat' => (isset($row[7]) && !empty(trim($row[7]))) ? trim($row[7]) : null,
                        'telepon' => (isset($row[8]) && !empty(trim($row[8]))) ? trim($row[8]) : null,
                        'email' => (isset($row[9]) && !empty(trim($row[9]))) ? trim($row[9]) : null,
                        'kelas_id' => (isset($row[10]) && !empty(trim($row[10]))) ? (int)$row[10] : null,
                        'jurusan_id' => (isset($row[11]) && !empty(trim($row[11]))) ? (int)$row[11] : null,
                        'tahun_masuk' => date('Y'),
                        'status' => (isset($row[12]) && !empty(trim($row[12]))) ? trim($row[12]) : 'aktif',
                        'nama_ayah' => (isset($row[13]) && !empty(trim($row[13]))) ? trim($row[13]) : null,
                        'nama_ibu' => (isset($row[14]) && !empty(trim($row[14]))) ? trim($row[14]) : null,
                        'pekerjaan_ayah' => (isset($row[15]) && !empty(trim($row[15]))) ? trim($row[15]) : null,
                        'pekerjaan_ibu' => (isset($row[16]) && !empty(trim($row[16]))) ? trim($row[16]) : null,
                        'telepon_orangtua' => (isset($row[17]) && !empty(trim($row[17]))) ? trim($row[17]) : null,
                        'alamat_orangtua' => (isset($row[19]) && !empty(trim($row[19]))) ? trim($row[19]) : null,
                        'password' => bcrypt($password),
                    ];
                    
                    \Log::info("Prepared data for siswa", [
                        'data' => array_merge($data, ['password' => '[HIDDEN]']),
                        'tanggal_lahir_value' => $tanggal_lahir,
                        'tanggal_lahir_is_null' => $tanggal_lahir === null
                    ]);
                    
                    // Basic validations
                    if (empty($data['nis']) || empty($data['nisn']) || empty($data['nama_lengkap'])) {
                        $error = "Baris " . ($i + 1) . ": NIS, NISN, dan Nama Lengkap harus diisi";
                        $errors[] = $error;
                        \Log::warning($error);
                        continue;
                    }
                    
                    // Check for duplicate NIS/NISN (skip if updating existing)
                    if (!$existing) {
                        $duplicateNis = Siswa::where('nis', $data['nis'])->first();
                        $duplicateNisn = Siswa::where('nisn', $data['nisn'])->first();
                        
                        if ($duplicateNis) {
                            $error = "Baris " . ($i + 1) . ": NIS {$data['nis']} sudah ada di database";
                            $errors[] = $error;
                            \Log::warning($error);
                            continue;
                        }
                        
                        if ($duplicateNisn) {
                            $error = "Baris " . ($i + 1) . ": NISN {$data['nisn']} sudah ada di database";
                            $errors[] = $error;
                            \Log::warning($error);
                            continue;
                        }
                    }
                    
                    // Validate kelas and jurusan exist (hanya jika diisi)
                    if ($data['kelas_id'] && !Kelas::find($data['kelas_id'])) {
                        $error = "Baris " . ($i + 1) . ": Kelas dengan ID {$data['kelas_id']} tidak ditemukan";
                        $errors[] = $error;
                        \Log::warning($error);
                        continue;
                    }
                    
                    if ($data['jurusan_id'] && !Jurusan::find($data['jurusan_id'])) {
                        $error = "Baris " . ($i + 1) . ": Jurusan dengan ID {$data['jurusan_id']} tidak ditemukan";
                        $errors[] = $error;
                        \Log::warning($error);
                        continue;
                    }
                    
                    \Log::info("About to create/update siswa", [
                        'is_update' => $existing ? true : false,
                        'tanggal_lahir' => $data['tanggal_lahir'],
                        'password_used' => $password
                    ]);
                    
                    if ($existing) {
                        // Update existing siswa
                        $existing->update($data);
                        $updated++;
                        \Log::info("Updated existing siswa", ['id' => $existing->id]);
                    } else {
                        // Create new siswa
                        $newSiswa = Siswa::create($data);
                        $imported++;
                        \Log::info("Created new siswa", ['id' => $newSiswa->id]);
                    }
                } catch (\Exception $e) {
                    $error = "Baris " . ($i + 1) . ": " . $e->getMessage();
                    $errors[] = $error;
                    \Log::error($error, ['exception' => $e]);
                }
            }
            
            \Log::info('Import process completed', [
                'imported' => $imported,
                'updated' => $updated,
                'errors' => count($errors)
            ]);
            
            $message = '';
            if ($imported > 0) {
                $message .= "Berhasil mengimpor {$imported} siswa baru. ";
            }
            if ($updated > 0) {
                $message .= "Berhasil memperbarui {$updated} siswa. ";
            }
            
            if ($imported == 0 && $updated == 0 && empty($errors)) {
                $message = 'Import selesai, namun tidak ada data yang diproses. Pastikan file berisi data yang valid.';
            }
            
            if (!empty($errors)) {
                $message .= "Terdapat " . count($errors) . " error: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " dan " . (count($errors) - 3) . " error lainnya.";
                }
            }

            return redirect()->route('admin.siswa.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Import siswa failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Gagal mengimpor data siswa: ' . $e->getMessage());
        }
    }

    public function template(Request $request)
    {
        return Excel::download(new \App\Exports\TemplateSiswaExport, 'template_import_siswa.xlsx');
    }

    /**
     * Bulk delete selected students.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('admin.siswa.index')->with('error', 'Tidak ada siswa yang dipilih untuk dihapus.');
        }

        DB::beginTransaction();
        try {
            $siswas = Siswa::whereIn('id', $ids)->get();
            foreach ($siswas as $siswa) {
                if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                $siswa->delete();
            }
            DB::commit();
            return redirect()->route('admin.siswa.index')->with('success', 'Berhasil menghapus siswa terpilih.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.siswa.index')->with('error', 'Gagal menghapus siswa: ' . $e->getMessage());
        }
    }

    public function resetPassword(Siswa $siswa)
    {
        try {
            // Reset password ke tanggal lahir dengan format DDMMYYYY
            if ($siswa->tanggal_lahir) {
                $birthDate = new \DateTime($siswa->tanggal_lahir);
                $password = $birthDate->format('dmY');
            } else {
                $password = $siswa->nis; // Fallback ke NIS jika tidak ada tanggal lahir
            }
            $siswa->update([
                'password' => $password
            ]);

            return redirect()->back()->with('success', 'Password siswa berhasil direset ke NIS siswa.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mereset password siswa.');
        }
    }

    /**
     * Bayar semua tagihan yang belum lunas untuk siswa tertentu
     */
    public function bayarSemuaTagihan($id)
    {
        $siswa = \App\Models\Siswa::with('pembayaran')->findOrFail($id);
        $tagihanList = \App\Models\Tagihan::where(function($q) use ($siswa) {
            $q->whereNull('kelas_id')->whereNull('siswa_id')  // Tagihan global
              ->orWhere('siswa_id', $siswa->id);              // Tagihan spesifik siswa
        })->get();

        foreach ($tagihanList as $tagihan) {
            $totalDibayar = $siswa->pembayaran->where('tagihan_id', $tagihan->id)->sum('jumlah');
            $sisa = $tagihan->nominal - $totalDibayar;
            if ($sisa > 0) {
                \App\Models\Pembayaran::create([
                    'siswa_id' => $siswa->id,
                    'tagihan_id' => $tagihan->id,
                    'keterangan' => $tagihan->nama_tagihan,
                    'jumlah' => $sisa,
                    'status' => 'Lunas',
                    'tanggal' => now(),
                ]);
            }
        }
        return redirect()->back()->with('success', 'Semua tagihan berhasil dibayarkan.');
    }
    
    /**
     * Helper method to format error messages in a user-friendly way
     * 
     * @param \Exception $e The exception
     * @return string Formatted error message
     */
    private function getFormattedErrorMessage(\Exception $e)
    {
        $message = $e->getMessage();
        
        // Check for common database constraints
        if (strpos($message, 'SQLSTATE[23000]') !== false) {
            // Handle integrity constraint violations
            if (strpos($message, 'Column \'nis\' cannot be null') !== false) {
                return 'NIS tidak boleh kosong. Silakan lengkapi data NIS siswa.';
            }
            if (strpos($message, 'Column \'nisn\' cannot be null') !== false) {
                return 'NISN tidak boleh kosong. Silakan lengkapi data NISN siswa.';
            }
            if (strpos($message, 'Column \'nama_lengkap\' cannot be null') !== false) {
                return 'Nama lengkap tidak boleh kosong. Silakan lengkapi data nama siswa.';
            }
            if (strpos($message, 'Column \'jenis_kelamin\' cannot be null') !== false) {
                return 'Jenis kelamin tidak boleh kosong. Silakan pilih jenis kelamin siswa.';
            }
            if (strpos($message, 'Column \'tempat_lahir\' cannot be null') !== false) {
                return 'Tempat lahir tidak boleh kosong. Silakan lengkapi data tempat lahir siswa.';
            }
            if (strpos($message, 'Column \'tanggal_lahir\' cannot be null') !== false) {
                return 'Tanggal lahir tidak boleh kosong. Silakan lengkapi data tanggal lahir siswa.';
            }
            if (strpos($message, 'Column \'agama\' cannot be null') !== false) {
                return 'Agama tidak boleh kosong. Silakan pilih agama siswa.';
            }
            if (strpos($message, 'Column \'kelas_id\' cannot be null') !== false) {
                return 'Kelas tidak boleh kosong. Silakan pilih kelas untuk siswa.';
            }
            if (strpos($message, 'Column \'jurusan_id\' cannot be null') !== false) {
                return 'Jurusan tidak boleh kosong. Silakan pilih jurusan untuk siswa.';
            }
            if (strpos($message, 'Duplicate entry') !== false) {
                if (strpos($message, 'for key \'siswa_nis_unique\'') !== false) {
                    return 'NIS yang dimasukkan sudah digunakan. Silakan gunakan NIS yang lain.';
                }
                if (strpos($message, 'for key \'siswa_nisn_unique\'') !== false) {
                    return 'NISN yang dimasukkan sudah digunakan. Silakan gunakan NISN yang lain.';
                }
                if (strpos($message, 'for key \'siswa_email_unique\'') !== false) {
                    return 'Email yang dimasukkan sudah digunakan. Silakan gunakan email yang lain.';
                }
                return 'Data yang dimasukkan sudah ada di sistem. Mohon periksa kembali data yang diinput.';
            }
            
            return 'Terjadi kesalahan pada data yang dimasukkan. Mohon periksa kembali dan pastikan semua data wajib telah diisi.';
        }
        
        // For all other exceptions
        return 'Gagal menambahkan data siswa. Silakan coba lagi atau hubungi administrator.';
    }
}
