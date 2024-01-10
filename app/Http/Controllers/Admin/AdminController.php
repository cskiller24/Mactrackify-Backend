<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Invite;
use App\Models\Team;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseItem;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $hasBack = false;
        $userCount = User::count();
        $invitationCount = Invite::count();
        $teamCount = Team::count();
        $warehouseCount = Warehouse::count();
        $warehouseItemCount = WarehouseItem::count();
        $accountsCount = Account::count();
        return view('admin.home', compact('hasBack', 'userCount', 'invitationCount', 'teamCount', 'warehouseCount', 'warehouseItemCount', 'accountsCount'));
    }
}
