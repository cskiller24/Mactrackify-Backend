<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Team;
use App\Models\Track;
use App\Models\User;
use Rap2hpoutre\FastExcel\FastExcel;

class TeamLeaderController extends Controller
{
    public function index()
    {

        return view('team-leader.index');
    }

    public function brandAmbassadorsIndex()
    {
        $team = Team::query()->whereHas('leaders', function ($query) {
            $query->where('users.id', auth()->id());
        })->with(['members.sales'])->first();

        return view('team-leader.brand-ambassadors', compact('team'));
    }

    public function dataIndex()
    {
        $sales = Sale::teamLeaderWide()->get();

        return view('team-leader.data', compact('sales'));
    }

    public function dataExport()
    {
        $sales = Sale::teamLeaderWide()->with(['team', 'teamLeader', 'brandAmbassador'])->get();

        $toExcel = $sales->map(function (Sale $sale) {
            return [
                'Name' => $sale->customer_name,
                'Contact Information' => $sale->customer_contact,
                'Age' => $sale->customer_age,
                'Product' => $sale->product,
                'Quantity' => $sale->product_quantity,
                'Promo' => $sale->promo,
                'Quantity' => $sale->promo_quantity,
                'Signature' => route('download', $sale->signatureUrl)
            ];
        });

        return (new FastExcel($toExcel))->download('sales-'.date('Y-m-d').'.xlsx');
    }

    public function trackingIndex()
    {
        $team = Team::teamLeaderWide()->whereHas('members')->first();

        $tracking = $team->members->map(fn ($user) => $user->latest_track);

        return inertia('Tracking', [
            'team' => $team,
            'tracking' => $tracking,
        ]);
    }

    public function trackingShow($id)
    {
        $user = User::query()->findOrFail($id);
        $user->load(['latestTracking']);

        abort_if(! $user->isBrandAmbassador(), 404);

        return inertia('TrackingDeployee', [
            'user' => $user
        ]);
    }

    public function notificationIndex()
    {
        /** @var User $user */
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->get();

        return view('team-leader.notifications', compact('notifications'));
    }
}
