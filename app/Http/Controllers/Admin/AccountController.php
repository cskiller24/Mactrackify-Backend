<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $total = Account::count();

        $accounts = Account::search($request->get('search', ''))->get();

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

    public function update(Request $request, Account $account)
    {
        $data = $request->validate([
            'name' => 'required',
            'account_number' => 'required',
            'address' => 'required',
        ]);

        $account->update($data);

        flash('Account updated successfully');

        return redirect()->route('admin.accounts.index');
    }

    public function addBalance(Request $request, Account $account)
    {
        $data = $request->validate([
            'balance' => 'required|numeric',
        ]);

        $account->update($data);

        flash('Added balance successfully');

        return redirect()->route('admin.accounts.index');
    }

    public function destroy(Account $account)
    {
        $account->balances()->delete();
        $account->transactions()->delete();
        $account->delete();

        flash('Account deleted successfully');

        return redirect()->route('admin.accounts.index');
    }
}
