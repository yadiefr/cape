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

    // DEBUG: Debug galeri file status
    public function debugGaleriFile($id)
    {
        $foto = GaleriFoto::findOrFail($id);
        $paths = HostingStorageHelper::getHostingPaths();
        
        $debug = [
            'foto_id' => $foto->id,
            'foto_path' => $foto->foto,
            'foto_url' => $foto->foto_url,
            'is_hosting' => HostingStorageHelper::isHostingEnvironment(),
            'paths' => $paths,
            'file_checks' => [
                'laravel_storage' => file_exists(storage_path('app/public/' . $foto->foto)),
                'public_storage' => file_exists($paths['public_storage'] . '/' . $foto->foto),
                'current_storage' => file_exists($paths['current_storage'] . '/' . $foto->foto),
            ]
        ];
        
        return response()->json($debug);
    }

    // DEBUG: Check all galeri files
    public function checkGaleriFiles()
    {
        $fotos = GaleriFoto::whereNotNull('foto')->get();
        $paths = HostingStorageHelper::getHostingPaths();
        
        $results = [];
        foreach ($fotos as $foto) {
            $results[] = [
                'id' => $foto->id,
                'path' => $foto->foto,
                'laravel_exists' => file_exists(storage_path('app/public/' . $foto->foto)),
                'public_exists' => file_exists($paths['public_storage'] . '/' . $foto->foto),
                'current_exists' => file_exists($paths['current_storage'] . '/' . $foto->foto),
                'url' => $foto->foto_url
            ];
        }
        
        return response()->json([
            'total_files' => count($results),
            'files' => $results,
            'paths' => $paths,
            'is_hosting' => HostingStorageHelper::isHostingEnvironment()
        ]);
    }

    // DEBUG: Sync legacy files
    public function syncLegacyFiles(Request $request)
    {
        $fotos = GaleriFoto::whereNotNull('foto')->get();
        $paths = HostingStorageHelper::getHostingPaths();
        $results = [];
        
        foreach ($fotos as $foto) {
            $sourceFile = storage_path('app/public/' . $foto->foto);
            $targetFile = $paths['public_storage'] . '/' . $foto->foto;
            
            if (file_exists($sourceFile) && !file_exists($targetFile)) {
                $targetDir = dirname($targetFile);
                if (!is_dir($targetDir)) {
                    \Illuminate\Support\Facades\File::makeDirectory($targetDir, 0755, true);
                }
                
                if (copy($sourceFile, $targetFile)) {
                    @chmod($targetFile, 0644);
                    $results[] = ['id' => $foto->id, 'path' => $foto->foto, 'status' => 'synced'];
                } else {
                    $results[] = ['id' => $foto->id, 'path' => $foto->foto, 'status' => 'failed'];
                }
            } else {
                $results[] = ['id' => $foto->id, 'path' => $foto->foto, 'status' => 'already_exists_or_no_source'];
            }
        }
        
        return response()->json([
            'message' => 'Sync completed',
            'results' => $results,
            'total_synced' => count(array_filter($results, fn($r) => $r['status'] === 'synced'))
        ]);
    }

    // DEBUG: Debug hosting environment
    public function debugHostingEnv()
    {
        $status = HostingStorageHelper::getHostingStatus();
        $status['galeri_specific'] = [
            'galeri_directory_exists' => is_dir(HostingStorageHelper::getHostingPaths()['public_storage'] . '/galeri'),
            'sample_galeri_path' => HostingStorageHelper::getHostingPaths()['public_storage'] . '/galeri/',
        ];
        
        return response()->json($status);
    }

    // DEBUG: Test galeri upload
    public function testGaleriUpload(Request $request)
    {
        $request->validate([
            'test_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $file = $request->file('test_file');
        $result = HostingStorageHelper::uploadFile($file, 'galeri');
        
        $paths = HostingStorageHelper::getHostingPaths();
        
        return response()->json([
            'upload_result' => $result,
            'is_hosting' => HostingStorageHelper::isHostingEnvironment(),
            'paths' => $paths,
            'file_checks' => [
                'laravel_storage' => file_exists(storage_path('app/public/' . $result)),
                'public_storage' => file_exists($paths['public_storage'] . '/' . $result),
                'current_storage' => file_exists($paths['current_storage'] . '/' . $result),
            ],
            'expected_url' => $result ? asset('storage/' . $result) : null
        ]);
    }
}
