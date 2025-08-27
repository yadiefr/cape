<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\GaleriFoto;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'all');
        $search = $request->get('search', '');
        
        $galeri = Galeri::with(['foto' => function($query) {
            $query->where('is_thumbnail', true)->orWhere('is_thumbnail', false)->limit(1);
        }])
            ->when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                           ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->when($kategori !== 'all', function ($query) use ($kategori) {
                return $query->where('kategori', $kategori);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        // Get available categories
        $kategoris = Galeri::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->pluck('kategori');
        
        return view('galeri.index', compact('galeri', 'kategoris', 'kategori', 'search'));
    }
    
    public function show($id)
    {
        $galeri = Galeri::with('foto')->findOrFail($id);
        return view('galeri.show', compact('galeri'));
    }
    
    public function getPhotos($id)
    {
        $galeri = Galeri::findOrFail($id);
        $photos = GaleriFoto::where('galeri_id', $id)->get();
        return response()->json($photos);
    }
}
