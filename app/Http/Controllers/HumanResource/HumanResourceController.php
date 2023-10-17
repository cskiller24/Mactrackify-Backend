<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Mail\SendAvailabilityNotification;
use App\Models\Deployment;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use App\Rules\MustBeBrandAmbassador;
use App\Rules\MustBeTeamLeader;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
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
        $teams = Team::all();

        $todayDeployments = Deployment::today()
            ->with('team')
            ->get()
            ->groupBy('team.name');
        $tommorowDeployments = Deployment::tommorow()
            ->with(['team', 'user'])
            ->get()
            ->groupBy('team.name');

        // $todayDeployments = Team::with(['deployments' => fn ($query) => $query->where('date', now()->toDateString())])
        // ->whereHas('deployments', function ($q) {
        //     $q->where('date', now()->toDateString());
        // })->get();

        return view('human-resource.deployment', compact('teams', 'todayDeployments', 'tommorowDeployments'));
    }

    public function deploymentCreate(Team $team)
    {
        if($team->hasDeploymentTommorow()) {
            flash("The selected team has already have a deployment today (". now()->toDateString() .").", 'error');
            return redirect()->back();
        }

        $team->load(['leaders', 'members']);

        return view('human-resource.deployment-create', compact('team'));
    }

    public function deploymentStore(Request $request, Team $team)
    {
        $data = $request->validate([
            'team_id' => ['required', 'exists:teams,id'],
            'team_leader' => ['required', new MustBeTeamLeader],
            'brand_ambassador' => ['required', 'array'],
            'brand_ambassador.*' => [new MustBeBrandAmbassador]
        ]);

        try {
            DB::beginTransaction();
            foreach ($data['brand_ambassador'] as $user) {
                Deployment::createForUser($user, $team->id);
                $user = User::query()->find($user);
                Mail::to($user->email)->send(new SendAvailabilityNotification($user));
            }

            $user = User::query()->find($data['team_leader']);
            Deployment::createForUser($data['team_leader'], $team->id);
            Mail::to($user->email)->send(new SendAvailabilityNotification($user));

            DB::commit();

            flash('Deployment for team '.$team->name.' created successfully');
            return redirect()->route('human-resource.deployment');
        }catch(\Exception $e) {
            DB::rollBack();

            Log::error('SQL ERROR', ['message' => $e->getMessage()]);

            flash('Something went wrong please try again', 'error');

            return redirect()->back();
        }

    }

    public function sendNotification(Deployment $deployment)
    {
        $user = $deployment->user;

        $user->statuses()->create([
            'status' => Status::PENDING,
        ]);

        Mail::to($user->email)->send(new SendAvailabilityNotification($user));

        flash('Notification send succesfully');

        return redirect()->route('human-resource.deployment');
    }

    public function deploymentRecommend(Deployment $deployment)
    {
        $team = $deployment->team;
        $team->load('members');
        $user = $deployment->user;

        return view('human-resource.deployment-recommend', compact('team', 'user'));
    }

    public function deploymentRecommendStore(Request $request, Deployment $deployment)
    {
        $user = User::query()->find($request->brand_ambassador);

        DB::beginTransaction();
        try {
            $newDeployment = Deployment::create([
                'user_id' => $user->id,
                'team_id' => $deployment->team_id,
                'date' => $deployment->date,
                'status' => Deployment::NO_RESPONSE
            ]);
            Mail::to($user->email)->send(new SendAvailabilityNotification($user));
            $deployment->delete();
            DB::commit();
            flash('Successfully replace brand ambassador');
        } catch (Exception $e) {
            flash('Something went wrong please try again');
            DB::rollBack();
            Log::error($e->getMessage());
        }
        return redirect()->route('human-resource.deployment');
    }
}
