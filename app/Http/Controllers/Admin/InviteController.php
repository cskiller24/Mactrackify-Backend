<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InviteCreated;
use App\Models\Invite;
use App\Models\User;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mail;
use Symfony\Component\HttpFoundation\Response;

class InviteController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.invites.index', [
            'invites' => Invite::search($request->get('search', ''))->get()
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'email' => ['required', 'unique:users,email'],
            'role' => ['required', 'in:'.implode(",", User::rolesList())]
        ]);

        $invite = Invite::query()->create($request->only(['email', 'role']));

        Mail::to($request->email)
            ->send(new InviteCreated($invite));

        flash('Invite send and created successfully');

        return redirect()->route('admin.invites.index');
    }

    public function update(Invite $invite, Request $request)
    {
        $request->validate([
            'email' => ['required', 'unique:users,email'],
            'role' => ['required', ['in:'.implode(",", User::rolesList())]]
        ]);

        $invite->update($request->only(['email', 'role']));

        if($request->input('resend')) {
            Mail::to($request->email)
                ->send(new InviteCreated($invite));
        }

        flash('Invite edit successfully');

        return redirect()->route('admin.invites.index');
    }

    public function resend(Invite $invite)
    {
        Mail::to($invite->email)
            ->send(new InviteCreated($invite));

        flash('Invite resend successfully');

        return redirect()->route('admin.invites.index');
    }

    public function accept(Invite $invite)
    {
        return view('guest.accept', compact('invite'));
    }

    public function register(Request $request, Invite $invite)
    {
        $request->validate([
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'email' => ['unique:users,email', 'exists:invites,email', ''],
            'password' => ['required', 'confirmed'],
            'role' => ['in:'.implode(',', User::rolesList()), 'required']
        ]);

        if($invite->email !== $request->input('email') ||
            $invite->role !== $request->input('role')
        ) {
            return redirect()
                ->route('invites.accept', $invite->code)
                ->withErrors(['message' => 'Email or roles does not match. Please try again']);
        }

        DB::beginTransaction();
        try {
            User::query()->create($request->only([
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'password'
            ]))->assignRole($request->input('role'));

            $invite->delete();
            DB::commit();

            flash('Account registered successfully!');
            return redirect()->route('login');
        } catch (QueryException $e) {
            DB::rollBack();
            Log::critical('Invitation creation error', $e->getMessage());
            flash('Something went wrong please try again', 'error');

            return redirect()->route('login');
        }
    }
}
