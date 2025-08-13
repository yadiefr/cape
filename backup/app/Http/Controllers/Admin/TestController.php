<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Test method to see if routing is working
     */
    public function testSettings()
    {
        $settings = \App\Models\SettingsJadwal::orderBy('hari')->get();
        return view('admin.jadwal.debug', compact('settings'));
    }
    
    /**
     * Debug route information
     */
    public function debugRoutes()
    {
        $settings = \App\Models\SettingsJadwal::orderBy('hari')->get();
        return view('admin.jadwal.debug', compact('settings'));
    }
    
    /**
     * Simple raw text response
     */
    public function simpleTest()
    {
        return "This is a simple test response. If you can see this, the route is working.";
    }
}
