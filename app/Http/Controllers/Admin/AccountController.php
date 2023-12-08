<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $total = Account::count();

        $accounts = Account::all();

        return view('admin.accounts.index', compact('total', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'account_number' => 'required',
            'address' => 'required',
        ]);

        Account::query()->create([
            'name' => $request->name,
            'number' => $request->account_number,
            'address' => $request->address
        ]);

        toastr('Account created successfully');

        return redirect()->route('admin.accounts.index');
    }
}
