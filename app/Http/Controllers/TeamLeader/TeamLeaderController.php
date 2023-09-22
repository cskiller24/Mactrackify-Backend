<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamLeaderController extends Controller
{
    public function index()
    {
        return view('team-leader.index');
    }
}
