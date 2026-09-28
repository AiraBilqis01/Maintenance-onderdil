<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TenagakerjaController extends Controller
{
    public function index()
    {
        return view('tenagakerja.dashboard'); 
    }
}
