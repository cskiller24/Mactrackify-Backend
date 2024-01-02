<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var User|null $user */
        $user = auth()->user();

        abort_if(!$user, 404);

        $layout = '';

        if($user->isAdmin()) {
            $layout = 'admin';
        }

        if($user->isBrandAmbassador()) {
            $layout = 'brand-ambassador';
        }

        if($user->isTeamLeader()) {
            $layout = 'team-leader';
        }

        if($user->isHumanResource()) {
            $layout = 'human-resource';
        }

        return view('profile', compact('user', 'layout'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string'],
            'middle_name' => ['nullable', 'string'],
            'last_name' => ['required', 'string'],
            'profile_image' => ['nullable', 'image']
        ]);
        /** @var User $user */
        $user = auth()->user();

        if($request->hasFile('profile_image')) {
            $profile = $request->file('profile_image')->store('', 'public_images');

            $user->update(['profile' => $profile]);
        }

        $user->update($data);

        flash('Successfully updated profile');
        return redirect()->route('profile.index');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed']
        ]);

        /** @var User $user */
        $user = auth()->user();

        if(! Hash::check($request->input('current_password'), $user->password)) {
            flash('Current password does not match', 'error');

            return redirect()->route('profile.index');
        }

        $user->update(['password' => bcrypt($request->input('new_password'))]);

        flash('Password changed successfully');

        return redirect()->route('profile.index');
    }
}
