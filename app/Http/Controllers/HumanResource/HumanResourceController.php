<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HumanResourceController extends Controller
{
    public function index()
    {
        return view('human-resource.index');
    }
}
