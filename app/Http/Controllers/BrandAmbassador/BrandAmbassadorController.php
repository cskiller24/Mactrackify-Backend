<?php

namespace App\Http\Controllers\BrandAmbassador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandAmbassadorController extends Controller
{
    public function index()
    {
        return view('brand-ambassador.index');
    }
}
