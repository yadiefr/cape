<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::with('author')
            ->aktif()
            ->where(function($query) {
                $query->where('target_role', 'all')
                      ->orWhere('target_role', 'guru');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('guru.pengumuman.index', compact('pengumuman'));
    }

    public function show($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        // Check if the announcement is for guru or all
        if (!in_array($pengumuman->target_role, ['guru', 'all'])) {
            abort(403, 'Unauthorized - Pengumuman ini tidak untuk guru');
        }

        return view('guru.pengumuman.show', compact('pengumuman'));
    }
}
