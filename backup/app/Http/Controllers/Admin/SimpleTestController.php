<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SimpleTestController extends Controller
{
    public function renderView()
    {
        Log::info('SimpleTestController renderView method called');
        return view('admin.jadwal.settings')->with([
            'settings' => [],
            'debug' => 'SimpleTestController executed at ' . now()
        ]);
    }
}
