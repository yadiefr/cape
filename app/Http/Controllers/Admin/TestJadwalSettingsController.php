<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class TestJadwalSettingsController extends Controller
{
    public function testSettings()
    {
        // Just a simple method to test if we can render the settings view
        return view('admin.jadwal.settings');
    }
}
