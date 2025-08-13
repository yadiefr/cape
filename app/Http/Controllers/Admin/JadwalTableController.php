<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Settings;

class JadwalTableController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function createTableView()
    {
        $kelas_list = \App\Models\Kelas::all();
        $guru_list = \App\Models\Guru::all();
        $mapel_list = \App\Models\MataPelajaran::all();
        $tahun_ajaran_aktif = \App\Models\Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1));
        return view('admin.jadwal.create-table', compact('kelas_list', 'guru_list', 'mapel_list', 'tahun_ajaran_aktif'));
    }
}
