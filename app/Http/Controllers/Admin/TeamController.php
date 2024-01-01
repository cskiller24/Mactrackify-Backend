<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use App\Rules\MustBeBrandAmbassador;
use App\Rules\MustBeTeamLeader;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $total = Team::count();
        $teams = Team::with('users')->search($request->get('search', ''))->get();

        $teamLeaderCreate = User::teamLeader()
            ->withoutTeam()
            ->get(); // Team Leader without teams

        $brandAmbassadorCreate = User::brandAmbassador()
            ->withoutTeam()
            ->get(); // Brand Ambassador without teams


        return view('admin.teams.index', compact('total', 'teams', 'teamLeaderCreate', 'brandAmbassadorCreate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'location' => ['required'],
            'team_leader' => ['required', new MustBeTeamLeader],
            'brand_ambassador' => ['required', 'array'],
            'brand_ambassador.*' => [new MustBeBrandAmbassador]
        ]);

        $team = Team::query()->create($request->only(['name', 'location']));
        $team->users()->attach($request->input('team_leader'), ['is_leader' => true]);
        $team->users()->attach($request->input('brand_ambassador'));

        flash('Team created successfully');

        return redirect()->route('admin.teams.index');
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => ['required'],
            'location' => ['required'],
            'team_leader' => ['required', new MustBeTeamLeader],
            'brand_ambassador' => ['required', 'array'],
            'brand_ambassador.*' => [new MustBeBrandAmbassador]
        ]);

        $team->update($request->only(['name', 'location']));
        $team->users()->detach();

        $team->users()->attach($request->input('team_leader'), ['is_leader' => true]);
        $team->users()->attach($request->input('brand_ambassador'));

        flash('Team updated successfully');

        return redirect()->route('admin.teams.index');
    }
}
