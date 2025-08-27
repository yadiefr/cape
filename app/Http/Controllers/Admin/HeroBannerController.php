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
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'button_text' => 'required|string|max:255',
            'button_url' => 'required|url',
        ]);

        $data = $request->only(['title', 'subtitle', 'description', 'button_text', 'button_url', 'is_active']);

        // Handle main image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('hero-banners', 'public');
            $data['image'] = $imagePath;
        }

        HeroBanner::create($data);

        return redirect()->route('admin.hero.index')->with('success', 'Hero banner berhasil ditambahkan');
    }

    public function edit(HeroBanner $hero)
    {
        return view('admin.hero.edit', compact('hero'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'is_active' => 'boolean'
        ]);

        $hero = HeroBanner::findOrFail($id);

        $data = $request->only(['title', 'subtitle', 'description', 'button_text', 'button_url']);
        $data['is_active'] = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($hero->image && file_exists(public_path('images/hero/' . $hero->image))) {
                unlink(public_path('images/hero/' . $hero->image));
            }

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/hero'), $filename);
            $data['image'] = $filename;
        }

        $hero->update($data);

        return redirect()->route('admin.hero.index')->with('success', 'Hero banner berhasil diperbarui.');
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
