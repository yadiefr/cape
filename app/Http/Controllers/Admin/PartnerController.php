<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\HostingStorageHelper;

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
            $logo = $request->file('logo');
            $logoPath = HostingStorageHelper::uploadFile($logo, 'partners');
            
            if (!$logoPath) {
                return redirect()->back()->with('error', 'Gagal mengupload logo partner. Silakan coba lagi.');
            }
            
            $validated['logo'] = $logoPath;
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
                Storage::disk('public')->delete($partner->logo);
                // Also delete from hosting paths
                if (HostingStorageHelper::isHostingEnvironment()) {
                    $paths = HostingStorageHelper::getHostingPaths();
                    $hostingFile = $paths['public_storage'] . '/' . $partner->logo;
                    if (file_exists($hostingFile)) {
                        @unlink($hostingFile);
                    }
                }
            }
            
            $logo = $request->file('logo');
            $logoPath = HostingStorageHelper::uploadFile($logo, 'partners');
            
            if (!$logoPath) {
                return redirect()->back()->with('error', 'Gagal mengupload logo partner. Silakan coba lagi.');
            }
            
            $validated['logo'] = $logoPath;
        }

        $partner->update($validated);

        return redirect()
            ->route('admin.partner.index')
            ->with('success', 'Partner berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
            // Also delete from hosting paths
            if (HostingStorageHelper::isHostingEnvironment()) {
                $paths = HostingStorageHelper::getHostingPaths();
                $hostingFile = $paths['public_storage'] . '/' . $partner->logo;
                if (file_exists($hostingFile)) {
                    @unlink($hostingFile);
                }
            }
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
