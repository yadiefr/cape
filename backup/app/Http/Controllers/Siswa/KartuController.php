<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Siswa;

class KartuController extends Controller
{
    public function index()
    {
        $siswa = Auth::user();
        return view('siswa.kartu.index', compact('siswa'));
    }

    public function download()
    {
        $siswa = Auth::user();
        // You'll want to implement the actual ID card generation logic here
        // For example, using a PDF library like TCPDF or DOMPDF
        // This is just a placeholder response
        return back()->with('info', 'Fitur download kartu siswa akan segera tersedia.');
    }
}
