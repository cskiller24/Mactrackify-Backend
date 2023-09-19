<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('guest.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if(! Auth::attempt($request->only(['email', 'password']))) {
            flash('Invalid credentials');
            return redirect()->route('login');
        }

        $user = User::query()->whereEmail($request->input('email'))->first();

        auth()->login($user);

        return $this->redirect($user);
    }
}
