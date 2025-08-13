<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroBannerController extends Controller
{
    public function index()
    {
        $heroBanners = HeroBanner::latest()->get();
        return view('admin.hero.index', compact('heroBanners'));
    }

    public function create()
    {
        return view('admin.hero.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'button_text' => 'required|string|max:50',
            'button_url' => 'required|url|max:255',
            'is_active' => 'boolean'
        ]);

        $imagePath = $request->file('image')->store('hero-banners', 'public');

        HeroBanner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'image' => $imagePath,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'is_active' => $request->is_active ?? false
        ]);

        return redirect()->route('admin.hero.index')
            ->with('success', 'Hero banner berhasil ditambahkan');
    }

    public function edit(HeroBanner $hero)
    {
        return view('admin.hero.edit', compact('hero'));
    }

    public function update(Request $request, HeroBanner $hero)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'button_text' => 'required|string|max:50',
            'button_url' => 'required|url|max:255',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($hero->image) {
                Storage::disk('public')->delete($hero->image);
            }
            $imagePath = $request->file('image')->store('hero-banners', 'public');
        } else {
            $imagePath = $hero->image;
        }

        $hero->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'image' => $imagePath,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'is_active' => $request->is_active ?? false
        ]);

        return redirect()->route('admin.hero.index')
            ->with('success', 'Hero banner berhasil diperbarui');
    }

    public function destroy(HeroBanner $hero)
    {
        if ($hero->image) {
            Storage::disk('public')->delete($hero->image);
        }
        
        $hero->delete();

        return redirect()->route('admin.hero.index')
            ->with('success', 'Hero banner berhasil dihapus');
    }

    public function toggleStatus(HeroBanner $hero)
    {
        $hero->update(['is_active' => !$hero->is_active]);
        
        return redirect()->route('admin.hero.index')
            ->with('success', 'Status hero banner berhasil diperbarui');
    }
}
