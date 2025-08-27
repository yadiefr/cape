<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use Illuminate\Support\Facades\Storage;
use App\Helpers\HostingStorageHelper;

class GaleriFotoController extends Controller
{
    // INDEX: List all photos for a galeri
    public function index($galeri_id)
    {
        $galeri = Galeri::with('foto')->findOrFail($galeri_id);
        return view('admin.galeri.foto.index', compact('galeri'));
    }

    // CREATE: Show form to upload new photos
    public function create($galeri_id)
    {
        $galeri = Galeri::findOrFail($galeri_id);
        return view('admin.galeri.foto.create', compact('galeri'));
    }

    // STORE: Save uploaded photos
    public function store(Request $request, $galeri_id)
    {
        $galeri = Galeri::findOrFail($galeri_id);
        $request->validate([
            'foto' => 'required',
            'foto.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $is_thumbnail = $request->has('is_thumbnail');
        $foto_ids = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $idx => $file) {
                $photoPath = HostingStorageHelper::uploadFile($file, 'galeri');
                
                if (!$photoPath) {
                    return redirect()->back()->with('error', 'Gagal mengupload foto galeri. Silakan coba lagi.');
                }
                
                $foto = GaleriFoto::create([
                    'galeri_id' => $galeri->id,
                    'foto' => $photoPath,
                    'is_thumbnail' => false,
                ]);
                $foto_ids[] = $foto->id;
            }
        }
        // Set thumbnail jika diminta
        if ($is_thumbnail && count($foto_ids)) {
            GaleriFoto::where('galeri_id', $galeri->id)->update(['is_thumbnail' => false]);
            GaleriFoto::where('id', $foto_ids[0])->update(['is_thumbnail' => true]);
        }
        return redirect()->route('admin.galeri.foto.index', $galeri->id)->with('success', 'Foto berhasil ditambahkan');
    }

    // EDIT: Show form to edit a photo (set thumbnail, ganti foto)
    public function edit($galeri_id, $foto_id)
    {
        $galeri = Galeri::findOrFail($galeri_id);
        $foto = GaleriFoto::findOrFail($foto_id);
        return view('admin.galeri.foto.edit', compact('galeri', 'foto'));
    }

    // UPDATE: Update photo or set as thumbnail
    public function update(Request $request, $galeri_id, $foto_id)
    {
        $galeri = Galeri::findOrFail($galeri_id);
        $foto = GaleriFoto::findOrFail($foto_id);
        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // Ganti foto jika ada upload baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($foto->foto) {
                Storage::disk('public')->delete($foto->foto);
                // Also delete from hosting paths
                if (HostingStorageHelper::isHostingEnvironment()) {
                    $paths = HostingStorageHelper::getHostingPaths();
                    $hostingFile = $paths['public_storage'] . '/' . $foto->foto;
                    if (file_exists($hostingFile)) {
                        @unlink($hostingFile);
                    }
                }
            }
            
            $file = $request->file('foto');
            $photoPath = HostingStorageHelper::uploadFile($file, 'galeri');
            
            if (!$photoPath) {
                return redirect()->back()->with('error', 'Gagal mengupload foto galeri. Silakan coba lagi.');
            }
            
            $foto->foto = $photoPath;
        }
        // Set thumbnail jika dicentang
        if ($request->has('is_thumbnail')) {
            GaleriFoto::where('galeri_id', $galeri->id)->update(['is_thumbnail' => false]);
            $foto->is_thumbnail = true;
        } else {
            $foto->is_thumbnail = false;
        }
        $foto->save();
        return redirect()->route('admin.galeri.foto.index', $galeri->id)->with('success', 'Foto berhasil diperbarui');
    }

    // DESTROY: Delete a photo
    public function destroy($galeri_id, $foto_id)
    {
        $foto = GaleriFoto::findOrFail($foto_id);
        
        // Hapus file foto jika ada
        if ($foto->foto) {
            Storage::disk('public')->delete($foto->foto);
            // Also delete from hosting paths
            if (HostingStorageHelper::isHostingEnvironment()) {
                $paths = HostingStorageHelper::getHostingPaths();
                $hostingFile = $paths['public_storage'] . '/' . $foto->foto;
                if (file_exists($hostingFile)) {
                    @unlink($hostingFile);
                }
            }
        }
        
        $foto->delete();
        return back()->with('success', 'Foto berhasil dihapus');
    }
}
