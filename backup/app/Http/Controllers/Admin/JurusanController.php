<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jurusan::with(['kepala']); // Eager load kepala jurusan
        
        // Filter berdasarkan status aktif
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Filter berdasarkan kode jurusan
        if ($request->has('kode') && !empty($request->kode)) {
            $query->where('kode_jurusan', 'like', '%' . $request->kode . '%');
        }

        // Filter berdasarkan nama jurusan
        if ($request->has('nama') && !empty($request->nama)) {
            $query->where('nama_jurusan', 'like', '%' . $request->nama . '%');
        }

        $jurusan = $query->orderBy('nama_jurusan', 'asc')->paginate(10);
        $guru_map = Guru::pluck('nama', 'id')->toArray();
        
        return view('admin.jurusan.index', compact('jurusan', 'guru_map'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guru_list = Guru::orderBy('nama')->get();
                        
        return view('admin.jurusan.create', compact('guru_list'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:10|unique:jurusan,kode_jurusan',
            'deskripsi' => 'required|string',
            'kepala_jurusan' => 'required|exists:guru,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_header' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'prospek_karir' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        
        // Handle logo upload if present
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = 'jurusan_logo_' . Str::slug($request->kode_jurusan) . '.' . $logo->getClientOriginalExtension();
            $logo->storeAs('public/jurusan/logo', $logoName);
            $data['logo'] = 'jurusan/logo/' . $logoName;
        }
        
        // Handle gambar header upload if present
        if ($request->hasFile('gambar_header')) {
            $header = $request->file('gambar_header');
            $headerName = 'jurusan_header_' . Str::slug($request->kode_jurusan) . '.' . $header->getClientOriginalExtension();
            $header->storeAs('public/jurusan/header', $headerName);
            $data['gambar_header'] = 'jurusan/header/' . $headerName;
        }
        
        // Set is_active default value if not provided
        $data['is_active'] = $request->has('is_active') ? $request->is_active : true;
        
        Jurusan::create($data);
        
        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jurusan $jurusan)
    {
        return view('admin.jurusan.show', compact('jurusan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jurusan $jurusan)
    {
        $guru_list = Guru::orderBy('nama')->get();
                        
        return view('admin.jurusan.edit', compact('jurusan', 'guru_list'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:10|unique:jurusan,kode_jurusan,' . $jurusan->id,
            'deskripsi' => 'required|string',
            'kepala_jurusan' => 'required|exists:guru,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_header' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'prospek_karir' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        
        // Handle logo upload if present
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($jurusan->logo && Storage::exists('public/' . $jurusan->logo)) {
                Storage::delete('public/' . $jurusan->logo);
            }
            
            $logo = $request->file('logo');
            $logoName = 'jurusan_logo_' . Str::slug($request->kode_jurusan) . '.' . $logo->getClientOriginalExtension();
            $logo->storeAs('public/jurusan/logo', $logoName);
            $data['logo'] = 'jurusan/logo/' . $logoName;
        }
        
        // Handle gambar header upload if present
        if ($request->hasFile('gambar_header')) {
            // Delete old header if exists
            if ($jurusan->gambar_header && Storage::exists('public/' . $jurusan->gambar_header)) {
                Storage::delete('public/' . $jurusan->gambar_header);
            }
            
            $header = $request->file('gambar_header');
            $headerName = 'jurusan_header_' . Str::slug($request->kode_jurusan) . '.' . $header->getClientOriginalExtension();
            $header->storeAs('public/jurusan/header', $headerName);
            $data['gambar_header'] = 'jurusan/header/' . $headerName;
        }
        
        // Set is_active value
        $data['is_active'] = $request->has('is_active') ? $request->is_active : false;
        
        $jurusan->update($data);
        
        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurusan $jurusan)
    {
        // Check if any classes, students, or subjects are associated with this jurusan
        if ($jurusan->kelas->count() > 0 || $jurusan->siswa->count() > 0 || $jurusan->mata_pelajaran->count() > 0) {
            return redirect()->route('admin.jurusan.index')
                ->with('error', 'Jurusan tidak dapat dihapus karena masih memiliki kelas, siswa, atau mata pelajaran terkait.');
        }
        
        // Delete logo and header if exists
        if ($jurusan->logo && Storage::exists('public/' . $jurusan->logo)) {
            Storage::delete('public/' . $jurusan->logo);
        }
        
        if ($jurusan->gambar_header && Storage::exists('public/' . $jurusan->gambar_header)) {
            Storage::delete('public/' . $jurusan->gambar_header);
        }
        
        $jurusan->delete();
        
        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
