<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Facades\Storage;
use App\Helpers\HostingStorageHelper;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::orderBy('created_at', 'desc')->get();
        return view('admin.berita.index', compact('berita'));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);
        
        $data = $request->only(['judul', 'isi']);
        
        // Handle foto upload
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoPath = HostingStorageHelper::uploadFile($foto, 'berita_foto');
            
            if (!$fotoPath) {
                return redirect()->back()->with('error', 'Gagal mengupload foto berita. Silakan coba lagi.');
            }
            
            $data['foto'] = $fotoPath;
        }
        
        // Handle lampiran upload
        if ($request->hasFile('lampiran')) {
            $lampiran = $request->file('lampiran');
            $lampiranPath = HostingStorageHelper::uploadFile($lampiran, 'lampiran_berita');
            
            if (!$lampiranPath) {
                return redirect()->back()->with('error', 'Gagal mengupload lampiran berita. Silakan coba lagi.');
            }
            
            $data['lampiran'] = $lampiranPath;
        }
        
        Berita::create($data);
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan');
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);
        
        $data = $request->only(['judul', 'isi']);
        
        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($berita->foto) {
                Storage::disk('public')->delete($berita->foto);
                // Also delete from hosting paths
                if (HostingStorageHelper::isHostingEnvironment()) {
                    $paths = HostingStorageHelper::getHostingPaths();
                    $hostingFile = $paths['public_storage'] . '/' . $berita->foto;
                    if (file_exists($hostingFile)) {
                        @unlink($hostingFile);
                    }
                }
            }
            
            $foto = $request->file('foto');
            $fotoPath = HostingStorageHelper::uploadFile($foto, 'berita_foto');
            
            if (!$fotoPath) {
                return redirect()->back()->with('error', 'Gagal mengupload foto berita. Silakan coba lagi.');
            }
            
            $data['foto'] = $fotoPath;
        }
        
        // Handle lampiran upload
        if ($request->hasFile('lampiran')) {
            // Hapus file lama jika ada
            if ($berita->lampiran) {
                Storage::disk('public')->delete($berita->lampiran);
                // Also delete from hosting paths
                if (HostingStorageHelper::isHostingEnvironment()) {
                    $paths = HostingStorageHelper::getHostingPaths();
                    $hostingFile = $paths['public_storage'] . '/' . $berita->lampiran;
                    if (file_exists($hostingFile)) {
                        @unlink($hostingFile);
                    }
                }
            }
            
            $lampiran = $request->file('lampiran');
            $lampiranPath = HostingStorageHelper::uploadFile($lampiran, 'lampiran_berita');
            
            if (!$lampiranPath) {
                return redirect()->back()->with('error', 'Gagal mengupload lampiran berita. Silakan coba lagi.');
            }
            
            $data['lampiran'] = $lampiranPath;
        }
        
        $berita->update($data);
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        
        // Hapus foto jika ada
        if ($berita->foto) {
            Storage::disk('public')->delete($berita->foto);
            // Also delete from hosting paths
            if (HostingStorageHelper::isHostingEnvironment()) {
                $paths = HostingStorageHelper::getHostingPaths();
                $hostingFile = $paths['public_storage'] . '/' . $berita->foto;
                if (file_exists($hostingFile)) {
                    @unlink($hostingFile);
                }
            }
        }
        
        // Hapus file lampiran jika ada
        if ($berita->lampiran) {
            Storage::disk('public')->delete($berita->lampiran);
            // Also delete from hosting paths
            if (HostingStorageHelper::isHostingEnvironment()) {
                $paths = HostingStorageHelper::getHostingPaths();
                $hostingFile = $paths['public_storage'] . '/' . $berita->lampiran;
                if (file_exists($hostingFile)) {
                    @unlink($hostingFile);
                }
            }
        }
        
        $berita->delete();
        
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus');
    }
}
