<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri;

class GaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'all');
        
        if ($kategori === 'all') {
            $galeri = Galeri::with('foto')->get();
        } else {
            $galeri = Galeri::with('foto')->where('kategori', $kategori)->get();
        }
        
        return view('admin.galeri.index', compact('galeri'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.galeri.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug information
        \Log::info('Galeri store request data:', [
            'post_data' => $request->all(),
            'files' => $request->hasFile('foto') ? 'Files present' : 'No files',
            'file_count' => $request->hasFile('foto') ? count($request->file('foto')) : 0
        ]);

        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'kategori' => 'required|string',
                'foto' => 'required|array|min:1',
                'foto.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB per file
                'thumbnail_index' => 'nullable|integer|min:0' // Made optional with fallback
            ], [
                'foto.required' => 'Minimal upload 1 foto',
                'foto.*.required' => 'Semua file harus berupa gambar',
                'foto.*.image' => 'File harus berupa gambar',
                'foto.*.mimes' => 'Format file harus: jpeg, png, jpg, atau gif',
                'foto.*.max' => 'Ukuran file maksimal 5MB'
            ]);

            // Process uploaded photos first to get thumbnail
            $thumbnailFileName = null;
            $photoData = [];
            
            if ($request->hasFile('foto')) {
                $photos = $request->file('foto');
                $thumbnailIndex = $request->thumbnail_index ?? 0; // Default to first photo if not specified

                foreach ($photos as $index => $photo) {
                    // Generate unique filename with timestamp and random string
                    $timestamp = now()->format('YmdHis');
                    $randomString = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 5);
                    $fileName = $timestamp . '_' . $randomString . '_' . $index . '.' . $photo->getClientOriginalExtension();
                    
                    // Move file to uploads directory
                    $photo->move(public_path('uploads/galeri'), $fileName);

                    // Prepare data for galeri_foto table
                    $isThunbnail = ($index == $thumbnailIndex);
                    $photoData[] = [
                        'foto' => $fileName,
                        'is_thumbnail' => $isThunbnail
                    ];

                    // Set thumbnail filename
                    if ($isThunbnail) {
                        $thumbnailFileName = $fileName;
                    }
                }
                
                // If no thumbnail was set, use the first photo as thumbnail
                if (!$thumbnailFileName && !empty($photoData)) {
                    $thumbnailFileName = $photoData[0]['foto'];
                    $photoData[0]['is_thumbnail'] = true;
                }
            }

            // Validate that we have a thumbnail before creating galeri
            if (!$thumbnailFileName) {
                throw new \Exception('Thumbnail tidak dapat diproses. Pastikan minimal 1 foto diupload.');
            }

            // Create galeri record with thumbnail
            $galeri = Galeri::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
                'gambar' => $thumbnailFileName // Set thumbnail as main gambar
            ]);

            // Save all photos to galeri_foto table
            foreach ($photoData as $data) {
                \App\Models\GaleriFoto::create([
                    'galeri_id' => $galeri->id,
                    'foto' => $data['foto'],
                    'is_thumbnail' => $data['is_thumbnail']
                ]);
            }

            return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil ditambahkan dengan ' . count($request->file('foto')) . ' foto');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Galeri validation error:', $e->validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi. Periksa data yang diinput.');
        } catch (\Exception $e) {
            \Log::error('Galeri store error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $galeri = Galeri::findOrFail($id);
        return view('admin.galeri.show', compact('galeri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $galeri = Galeri::findOrFail($id);
        return view('admin.galeri.edit', compact('galeri'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string',
            'foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'thumbnail_index' => 'nullable|integer|min:0'
        ]);

        $galeri = Galeri::findOrFail($id);

        // Update basic info
        $galeri->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori
        ]);

        // Handle new photos if uploaded
        if ($request->hasFile('foto')) {
            $photos = $request->file('foto');
            $thumbnailIndex = $request->thumbnail_index ?? 0;
            $thumbnailFileName = null;

            // Delete existing photos and files
            $existingPhotos = $galeri->foto;
            foreach ($existingPhotos as $photo) {
                if ($photo->foto && file_exists(public_path('uploads/galeri/' . $photo->foto))) {
                    unlink(public_path('uploads/galeri/' . $photo->foto));
                }
                $photo->delete();
            }

            // Process new photos
            foreach ($photos as $index => $photo) {
                $fileName = time() . '_' . $index . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads/galeri'), $fileName);

                $isThunbnail = ($index == $thumbnailIndex);
                
                \App\Models\GaleriFoto::create([
                    'galeri_id' => $galeri->id,
                    'foto' => $fileName,
                    'is_thumbnail' => $isThunbnail
                ]);

                if ($isThunbnail) {
                    $thumbnailFileName = $fileName;
                }
            }

            // Update main gambar field
            if ($thumbnailFileName) {
                $galeri->update(['gambar' => $thumbnailFileName]);
            }
        }

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $galeri = Galeri::with('foto')->findOrFail($id);

        // Delete all related photos from storage
        foreach ($galeri->foto as $photo) {
            if ($photo->foto && file_exists(public_path('uploads/galeri/' . $photo->foto))) {
                unlink(public_path('uploads/galeri/' . $photo->foto));
            }
        }

        // Delete main gambar if exists and different from photos
        if ($galeri->gambar && file_exists(public_path('uploads/galeri/' . $galeri->gambar))) {
            unlink(public_path('uploads/galeri/' . $galeri->gambar));
        }

        // Delete galeri record (will cascade delete photos due to foreign key)
        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri dan semua foto berhasil dihapus');
    }
} 