<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SimpleTestController extends Controller
{
    public function renderView()
    {
        return view('admin.simple-test.index');
    }
}
