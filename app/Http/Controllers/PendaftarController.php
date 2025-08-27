<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftarController extends Controller
{
    public function dashboard()
    {
        $user = Auth::guard('pendaftar')->user();
        $pendaftaran = $user->pendaftaran;
        
        return view('ppdb.dashboard', compact('pendaftaran'));
    }
}
