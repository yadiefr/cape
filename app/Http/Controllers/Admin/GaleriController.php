<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use App\Helpers\HostingStorageHelper;

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
        $maxFileSize = $this->getUploadMaxFilesize();

        return view('admin.galeri.create', compact('maxFileSize'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Get upload limits
            $maxFileSize = $this->getUploadMaxFilesize() * 1024; // Convert to KB for validation
            
            // Simple validation
            $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'kategori' => 'required|string',
                'foto' => 'required|array|min:1|max:20',
                'foto.*' => "required|image|mimes:jpeg,png,jpg,gif,webp|max:{$maxFileSize}",
                'thumbnail_index' => 'nullable|integer|min:0'
            ], [
                'foto.required' => 'Minimal upload 1 foto',
                'foto.min' => 'Minimal upload 1 foto',
                'foto.max' => 'Maksimal upload 20 foto',
                'foto.*.required' => 'Semua file harus berupa gambar',
                'foto.*.image' => 'File harus berupa gambar',
                'foto.*.mimes' => 'Format file harus: JPEG, PNG, JPG, GIF, atau WEBP',
                'foto.*.max' => "Ukuran file maksimal {$this->getUploadMaxFilesize()}MB"
            ]);

            // Process files
            $thumbnailIndex = (int) ($request->thumbnail_index ?? 0);
            $uploadedFiles = [];
            $thumbnailFile = null;

            \Log::info('Processing galeri upload:', [
                'judul' => $request->judul,
                'foto_count' => count($request->file('foto')),
                'thumbnail_index' => $thumbnailIndex
            ]);

            foreach ($request->file('foto') as $index => $file) {
                $uploadedPath = HostingStorageHelper::uploadFile($file, 'galeri');

                if ($uploadedPath) {
                    $isThumb = ($index === $thumbnailIndex);
                    $filename = basename($uploadedPath);

                    $uploadedFiles[] = [
                        'path' => $uploadedPath,
                        'filename' => $filename,
                        'is_thumbnail' => $isThumb
                    ];

                    if ($isThumb) {
                        $thumbnailFile = $filename;
                    }
                } else {
                    
                    Log::warning('File upload failed during galeri creation', ['original_name' => $file->getClientOriginalName()]);
                }
                
                \Log::info("File {$index} processed:", [
                    'filename' => $filename,
                    'is_thumbnail' => $isThumb
                ]);
            }

            // Ensure we have a thumbnail
            if (!$thumbnailFile && !empty($uploadedFiles)) {
                $thumbnailFile = $uploadedFiles[0]['filename'];
                $uploadedFiles[0]['is_thumbnail'] = true;
            }

            if (empty($uploadedFiles)) {
                return redirect()->back()->withInput()->with('error', 'Tidak ada foto yang berhasil diupload. Periksa log untuk detail.');
            }

            // Create galeri record
            $galeri = Galeri::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
                'gambar' => $thumbnailFile
            ]);

            // Save photos to galeri_foto table
            foreach ($uploadedFiles as $fileData) {
                \App\Models\GaleriFoto::create([
                    'galeri_id' => $galeri->id,
                    'foto' => $fileData['filename'], // Store only the filename
                    'is_thumbnail' => $fileData['is_thumbnail']
                ]);
            }

            \Log::info('Galeri created successfully:', [
                'id' => $galeri->id,
                'thumbnail' => $thumbnailFile,
                'files_count' => count($uploadedFiles)
            ]);

            return redirect()
                ->route('admin.galeri.index')
                ->with('success', "Galeri '{$request->judul}' berhasil ditambahkan dengan " . count($uploadedFiles) . " foto");

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Galeri validation failed:', $e->errors());
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Data tidak valid. Periksa kembali input Anda.');

        } catch (\Exception $e) {
            \Log::error('Galeri creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan galeri: ' . $e->getMessage());
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
        $galeri = Galeri::with('foto')->findOrFail($id);
        
        // Get PHP upload limits
        $maxFileSize = $this->getUploadMaxFilesize();
        
        return view('admin.galeri.edit', compact('galeri', 'maxFileSize'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Get upload limits
            $maxFileSize = $this->getUploadMaxFilesize() * 1024; // Convert to KB for validation
            
            // Validation
            $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'kategori' => 'required|string',
                'foto' => 'nullable|array|max:20',
                'foto.*' => "nullable|image|mimes:jpeg,png,jpg,gif,webp|max:{$maxFileSize}",
                'thumbnail_index' => 'nullable|integer|min:0',
                'current_thumbnail_id' => 'nullable|integer'
            ], [
                'foto.max' => 'Maksimal upload 20 foto',
                'foto.*.image' => 'File harus berupa gambar',
                'foto.*.mimes' => 'Format file harus: JPEG, PNG, JPG, GIF, atau WEBP',
                'foto.*.max' => "Ukuran file maksimal {$this->getUploadMaxFilesize()}MB"
            ]);

            $galeri = Galeri::findOrFail($id);

            \Log::info('Galeri update started:', [
                'id' => $id,
                'judul' => $request->judul,
                'has_new_files' => $request->hasFile('foto'),
                'new_files_count' => $request->hasFile('foto') ? count($request->file('foto')) : 0,
                'thumbnail_index' => $request->thumbnail_index,
                'current_thumbnail_id' => $request->current_thumbnail_id
            ]);

            // Update basic info
            $galeri->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
            ]);

            // Handle photo updates
            if ($request->hasFile('foto')) {
                // New photos uploaded - replace all existing photos
                $this->handleNewPhotosUpload($request, $galeri);
            } else {
                // No new photos - just update thumbnail if needed
                $this->handleThumbnailUpdate($request, $galeri);
            }

            return redirect()
                ->route('admin.galeri.index')
                ->with('success', "Galeri '{$request->judul}' berhasil diperbarui");

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Galeri update validation failed:', $e->errors());
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Data tidak valid. Periksa kembali input Anda.');

        } catch (\Exception $e) {
            \Log::error('Galeri update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui galeri: ' . $e->getMessage());
        }
    }

    /**
     * Handle new photos upload (replaces all existing photos)
     */
    private function handleNewPhotosUpload(Request $request, $galeri)
    {
        $photos = $request->file('foto');
        $thumbnailIndex = (int) ($request->thumbnail_index ?? 0);
        
        \Log::info('Processing new photos upload:', [
            'galeri_id' => $galeri->id,
            'photo_count' => count($photos),
            'thumbnail_index' => $thumbnailIndex
        ]);

        // Delete existing photos and files
        $existingPhotos = $galeri->foto;
        foreach ($existingPhotos as $photo) {
            if ($photo->foto) {
                HostingStorageHelper::deleteFile($photo->foto);
                \Log::info('Deleted existing photo file:', ['file' => $photo->foto]);
            }
            $photo->delete();
        }

        // Process new photos
        $uploadedFiles = [];
        $thumbnailFile = null;

        foreach ($photos as $index => $photo) {
            $uploadedPath = HostingStorageHelper::uploadFile($photo, 'galeri');

            if ($uploadedPath) {
                $isThumb = ($index === $thumbnailIndex);
                $filename = basename($uploadedPath);

                $uploadedFiles[] = [
                    'path' => $uploadedPath,
                    'filename' => $filename,
                    'is_thumbnail' => $isThumb
                ];

                if ($isThumb) {
                    $thumbnailFile = $filename;
                }
            } else {
                Log::warning('File upload failed during galeri update', ['original_name' => $photo->getClientOriginalName()]);
            }
            
            \Log::info("New photo processed:", [
                'index' => $index,
                'filename' => $filename,
                'is_thumbnail' => $isThumb
            ]);
        }

        // Ensure we have a thumbnail
        if (!$thumbnailFile && !empty($uploadedFiles)) {
            $thumbnailFile = $uploadedFiles[0]['filename'];
            $uploadedFiles[0]['is_thumbnail'] = true;
        }

        // Save photos to galeri_foto table
        foreach ($uploadedFiles as $fileData) {
            \App\Models\GaleriFoto::create([
                'galeri_id' => $galeri->id,
                'foto' => $fileData['filename'], // Store only the filename
                'is_thumbnail' => $fileData['is_thumbnail']
            ]);
        }

        // Update main galeri gambar field
        if ($thumbnailFile) {
            $galeri->update(['gambar' => $thumbnailFile]);
        }

        \Log::info('New photos upload completed:', [
            'galeri_id' => $galeri->id,
            'thumbnail_file' => $thumbnailFile,
            'total_photos' => count($uploadedFiles)
        ]);
    }

    /**
     * Handle thumbnail update for existing photos
     */
    private function handleThumbnailUpdate(Request $request, $galeri)
    {
        $currentThumbnailId = $request->current_thumbnail_id;
        
        if (!$currentThumbnailId) {
            \Log::info('No thumbnail update requested for galeri:', ['id' => $galeri->id]);
            return;
        }

        \Log::info('Processing thumbnail update:', [
            'galeri_id' => $galeri->id,
            'new_thumbnail_id' => $currentThumbnailId
        ]);

        // Verify the photo belongs to this galeri
        $newThumbnailPhoto = \App\Models\GaleriFoto::where('id', $currentThumbnailId)
            ->where('galeri_id', $galeri->id)
            ->first();

        if (!$newThumbnailPhoto) {
            throw new \Exception('Photo tidak ditemukan atau tidak termasuk dalam galeri ini');
        }

        // Update all photos in this galeri to not be thumbnail
        \App\Models\GaleriFoto::where('galeri_id', $galeri->id)
            ->update(['is_thumbnail' => false]);

        // Set the selected photo as thumbnail
        $newThumbnailPhoto->update(['is_thumbnail' => true]);

        // Update main galeri gambar field
        // The 'foto' column now stores only the filename, so we can use it directly
        $galeri->update(['gambar' => basename($newThumbnailPhoto->foto)]);

        \Log::info('Thumbnail updated successfully:', [
            'galeri_id' => $galeri->id,
            'new_thumbnail_photo_id' => $currentThumbnailId,
            'new_thumbnail_file' => $newThumbnailPhoto->foto
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $galeri = Galeri::with('foto')->findOrFail($id);

        // Delete all related photos from storage using the helper
        foreach ($galeri->foto as $photo) {
            if ($photo->foto) {
                HostingStorageHelper::deleteFile($photo->foto);
            }
        }

        // The main 'gambar' is one of the 'foto', so it's already deleted.

        // Delete galeri record (will cascade delete photos due to foreign key)
        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri dan semua foto berhasil dihapus');
    }

    /**
     * Get PHP upload max filesize in MB
     */
    private function getUploadMaxFilesize()
    {
        $uploadMax = ini_get('upload_max_filesize');
        $postMax = ini_get('post_max_size');

        // Convert to bytes for comparison
        $uploadMaxBytes = $this->parseSize($uploadMax);
        $postMaxBytes = $this->parseSize($postMax);

        // Use the smaller value
        $maxBytes = min($uploadMaxBytes, $postMaxBytes);

        // Convert back to MB and round down
        return floor($maxBytes / (1024 * 1024));
    }

    /**
     * Parse size string like '2M', '512K' to bytes
     */
    private function parseSize($size)
    {
        $unit = strtoupper(substr($size, -1));
        $value = (int) substr($size, 0, -1);

        switch ($unit) {
            case 'G':
                return $value * 1024 * 1024 * 1024;
            case 'M':
                return $value * 1024 * 1024;
            case 'K':
                return $value * 1024;
            default:
                return (int) $size;
        }
    }
}
