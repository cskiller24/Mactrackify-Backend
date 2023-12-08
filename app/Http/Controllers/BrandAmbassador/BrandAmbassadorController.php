<?php

namespace App\Http\Controllers\BrandAmbassador;

use App\Http\Controllers\Controller;
use App\Models\Deployment;
use App\Models\Sale;
use App\Models\Status;
use App\Models\TaskScheduler;
use App\Models\Team;
use App\Models\Track;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpParser\Node\Expr\Cast\Array_;

class BrandAmbassadorController extends Controller
{
    public function index()
    {
        return view('brand-ambassador.index');
    }

    public function dataIndex()
    {
        $team = auth()->user()->teams->first();
        $teamLeader = $team->leaders->first();
        $sales = Sale::brandAmbassadorWide()->get();

        return view('brand-ambassador.data', compact('sales', 'teamLeader', 'team'));
    }

    public function dataStore(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_contact' => 'required',
            'customer_age' => 'required|gt:17',
            'product' => 'required',
            'product_quantity' => 'required',
            'promo' => 'required',
            'promo_quantity' => 'required',
            'signature' => 'required'
        ]);

        $signature = $request->file('signature')->store('', 'customer_images');

        Sale::query()->create([
            'team_id' => $request->input('team_id'),
            'team_leader_id' => $request->input('team_leader_id'),
            'brand_ambassador_id' => auth()->id(),
            'customer_name' => $request->input('customer_name'),
            'customer_contact' => $request->input('customer_contact'),
            'customer_age' => $request->input('customer_age'),
            'product' => $request->input('product'),
            'product_quantity' => $request->input('product_quantity'),
            'promo' => $request->input('promo'),
            'promo_quantity' => $request->input('promo_quantity'),
            'signature' => $signature
        ]);

        flash('Sales added succesfully');

        return redirect()->route('brand-ambassador.data');
    }

    public function trackingIndex()
    {
        $tracks = Track::query()->whereBrandAmbassadorId(auth()->id())->latest()->get();
        $isTracking = TaskScheduler::query()
            ->whereCommand("app:test-ba-track")
            ->whereArguments(json_encode(['--user-id' => auth()->id()]))
            ->exists();

        return view('brand-ambassador.tracking', compact('tracks', 'isTracking'));
    }

    public function toggleTracking()
    {
        $command = "app:test-ba-track";
        $tasks = TaskScheduler::query()
            ->whereCommand($command)
            ->whereArguments(json_encode(['--user-id' => auth()->id()]))
            ->first();

        if($tasks) {
            $tasks->delete();

            flash('Test tracking disabled successfully');
        } else {
            TaskScheduler::query()->create([
                'command' => $command,
                'frequency' => 'everyMinute',
                'arguments' => json_encode(['--user-id' => auth()->id()]),
            ]);

            flash('Test tracking enabled successfully, create track every minute');
        }

        return redirect()->route('brand-ambassador.tracking');
    }

    public function scheduleIndex()
    {
        $todayDeployment = Deployment::today()->whereUserId(auth()->id())->first();
        $tommorowDeployment = Deployment::tommorow()->whereUserId(auth()->id())->first();
        dd($tommorowDeployment);

        return view('brand-ambassador.schedule', compact('todayDeployment', 'tommorowDeployment'));
    }

    public function scheduleUpdate(Request $request, Deployment $deployment)
    {
        $request->validate([
            'status' => ['in:'.Deployment::ACCEPTED.','.Deployment::DECLINED]
        ]);

        $user = auth()->user();

        $currentStatus = $request->input('status') === Deployment::ACCEPTED ? Status::AVAILABLE : Status::NOT_AVAILABLE;
        $user->statuses()->create([
            'status' => $currentStatus,
        ]);
        $deployment->update(['status' => $request->input('status')]);

        flash('You have updated your status successfully');

        return redirect()->route('brand-ambassador.schedule');
    }
}
