<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Mail\SendAvailabilityNotification;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Mail;

class HumanResourceController extends Controller
{
    public function index()
    {
        return view('human-resource.index');
    }

    public function brandAmbassadorsIndex()
    {
        $brandAmbassadors = User::brandAmbassador()->get();

        return view('human-resource.brand-ambassador', compact('brandAmbassadors'));
    }

    public function deploymentIndex()
    {
        $teams = Team::with(['leaders', 'members'])->get();

        return view('human-resource.deployment', compact('teams'));
    }

    public function sendNotification(User $user)
    {
        $user->statuses()->create([
            'status' => Status::PENDING,
        ]);

        Mail::to($user->email)->send(new SendAvailabilityNotification($user));

        flash('Notification send succesfully');

        return redirect()->route('human-resource.deployment');
    }

    public function sendAllNotification(Team $team)
    {
        $team->users->each(function (User $user) {
            Mail::to($user->email)->send(new SendAvailabilityNotification($user));
        });
    }
}
