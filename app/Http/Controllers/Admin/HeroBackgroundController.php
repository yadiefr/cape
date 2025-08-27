<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroBackground;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroBackgroundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $backgrounds = HeroBackground::latest()->get();
        return view('admin.hero-background.index', compact('backgrounds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hero-background.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'opacity' => 'required|numeric|min:0|max:1'
        ]);

        $data = $request->only(['opacity']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('hero-backgrounds', 'public');
            $data['image'] = $imagePath;
        }

        HeroBackground::create($data);
        return redirect()->route('admin.hero-background.index')->with('success', 'Background hero berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(HeroBackground $heroBackground)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HeroBackground $heroBackground)
    {
        return view('admin.hero-background.edit', compact('heroBackground'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HeroBackground $heroBackground)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'opacity' => 'required|numeric|min:0|max:1'
        ]);

        $data = $request->only(['opacity']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($heroBackground->image) {
                Storage::disk('public')->delete($heroBackground->image);
            }
            $data['image'] = $request->file('image')->store('hero-backgrounds', 'public');
        }

        $heroBackground->update($data);
        return redirect()->route('admin.hero-background.index')->with('success', 'Background hero berhasil diperbarui');
    }

    public function destroy(HeroBackground $heroBackground)
    {
        // Delete image file
        if ($heroBackground->image) {
            Storage::disk('public')->delete($heroBackground->image);
        }

        $heroBackground->delete();

        return redirect()->route('admin.hero-background.index')->with('success', 'Background hero berhasil dihapus');
    }

    /**
     * Toggle status
     */
    public function toggleStatus(HeroBackground $heroBackground)
    {
        $heroBackground->update(['is_active' => !$heroBackground->is_active]);
        
        return redirect()->route('admin.hero-background.index')
            ->with('success', 'Status background hero berhasil diperbarui');
    }
}
