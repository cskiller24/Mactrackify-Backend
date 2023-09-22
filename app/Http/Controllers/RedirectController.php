<?php

namespace App\Http\Controllers;

class RedirectController
{
    /**
     * @param \App\Models\User $user
     */
    public function redirect($user = null)
    {
        if(! $user) {
            /** @var \App\Models\User $user */
            $user = auth()->user();
        }

        if(! $user) {
            return redirect()->route('login');
        }

        if($user->isAdmin()) {
            return redirect('/admin');
        }

        if($user->isTeamLeader()) {
            return redirect('/team-leader');
        }

        if($user->isHumanResource()) {
            return redirect('/human-resource');
        }

        if($user->isBrandAmbassador()) {
            return redirect('/brand-ambassador');
        }

        return redirect()->route('login');
    }
}
