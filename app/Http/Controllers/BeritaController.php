<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;

class BeritaController extends Controller
{
    /**
     * Display a listing of berita (public page).
     */
    public function index(Request $request)
    {
        $query = Berita::query();
        
        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul', 'like', '%' . $searchTerm . '%')
                  ->orWhere('isi', 'like', '%' . $searchTerm . '%');
            });
        }
        
        $berita = $query->orderBy('created_at', 'desc')->paginate(12);
        
        return view('berita.index', compact('berita'));
    }

    /**
     * Display the specified berita.
     */
    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        
        // Get related berita (latest 6 excluding current)
        $relatedBerita = Berita::where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        return view('berita.show', compact('berita', 'relatedBerita'));
    }
}
