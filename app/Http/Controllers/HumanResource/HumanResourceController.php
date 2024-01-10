<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeploymentRequest;
use App\Mail\SendAvailabilityNotification;
use App\Models\Deployment;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use App\Rules\MustBeBrandAmbassador;
use App\Rules\MustBeTeamLeader;
use Carbon\Carbon;
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

    public function brandAmbassadorsIndex(Request $request)
    {
        $brandAmbassadors = User::brandAmbassador()->search($request->get('search', ''))->get();

        return view('human-resource.brand-ambassador', compact('brandAmbassadors'));
    }

    public function deploymentIndex()
    {
        $teams = Team::all();

        $deployments = Deployment::with('team')
            ->get()
            ->groupBy('date');

        return view('human-resource.deployment', compact('teams', 'deployments'));
    }

    public function deploymentCreate(Team $team)
    {
        $team->load(['leaders', 'members']);

        return view('human-resource.deployment-create', compact('team'));
    }

    public function deploymentStore(DeploymentRequest $request, Team $team)
    {
        $data = $request->validated();

        if(Carbon::parse($request->input('date'))->lessThan(Carbon::now()->yesterday())) {
            toastr()->error('You cannot create a deployment on past dates');

            return redirect()->route('human-resource.deployment.create', $team->id);
        }

        try {
            DB::beginTransaction();
            foreach ($data['brand_ambassador'] as $user) {
                $hasDeployment = Deployment::query()
                    ->whereUserId($user)
                    ->whereTeamId($team->id)
                    ->where('date', $request->input('date'))
                    ->exists();

                if($hasDeployment) {
                    continue;
                }

                $deployment = Deployment::createForUser($user, $team->id, $request->input('date'));
                $user = User::query()->find($user);
                $user->statuses()->create([
                    'status' => Status::PENDING
                ]);
                Mail::to($user->email)->send(new SendAvailabilityNotification($user, $deployment));
            }

            $hasDeployment = Deployment::query()
                ->whereUserId($data['team_leader'])
                ->whereTeamId($team->id)
                ->where('date', $request->input('date'))
                ->exists();
            if(! $hasDeployment) {
                $user = User::query()->find($data['team_leader']);
                $user->statuses()->create([
                    'status' => Status::PENDING
                ]);
                $deployment = Deployment::createForUser($data['team_leader'], $team->id, $request->input('date'));
                Mail::to($user->email)->send(new SendAvailabilityNotification($user, $deployment));
            }
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

        Mail::to($user->email)->send(new SendAvailabilityNotification($user, $deployment));

        flash('Notification send succesfully');

        return redirect()->route('human-resource.deployment');
    }

    public function deploymentReplaceAuto(Deployment $deployment)
    {

        $team = $deployment->team;
        $members = $team->members;
        foreach($members as $user) {
            if(! $user->hasDeployment($deployment->date)) {
                $deployment->update(['replaced' => true]);
                $newDeployment = Deployment::createForUser($user->id, $deployment->team_id, $deployment->date);
                $user->statuses()->create([
                    'status' => Status::PENDING
                ]);
                Mail::to($user->email)->send(new SendAvailabilityNotification($user, $newDeployment));

                toastr('Automatically replaced deployment successfully');
                return redirect()->route('human-resource.deployment');
            }
        };

        toastr()->info('All of the deployee for '.$deployment->date.' already has deployments');

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
