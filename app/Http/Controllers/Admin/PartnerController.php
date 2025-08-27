<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('order')->get();
        return view('admin.partner.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partner.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/partners');
            $validated['logo'] = str_replace('public/', '', $path);
        }

        Partner::create($validated);

        return redirect()
            ->route('admin.partner.index')
            ->with('success', 'Partner berhasil ditambahkan.');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partner.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($partner->logo) {
                Storage::delete('public/' . $partner->logo);
            }
            $path = $request->file('logo')->store('public/partners');
            $validated['logo'] = str_replace('public/', '', $path);
        }

        $partner->update($validated);

        return redirect()
            ->route('admin.partner.index')
            ->with('success', 'Partner berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo) {
            Storage::delete('public/' . $partner->logo);
        }
        
        $partner->delete();

        return redirect()
            ->route('admin.partner.index')
            ->with('success', 'Partner berhasil dihapus.');
    }

    public function toggleStatus(Partner $partner)
    {
        $partner->update([
            'is_active' => !$partner->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui.',
            'is_active' => $partner->is_active
        ]);
    }
}
