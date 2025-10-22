<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form for admin/organisateur.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile form for front users.
     */
    public function editFront(Request $request): View
    {
        return view('profile.editfront', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Redirect users to the appropriate profile page.
     */
    public function redirectToProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->isAdmin() || $user->isOrganisateur()) {
            return redirect()->route('profile.edit');
        } else {
            return redirect()->route('profile.editfront');
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // Redirect back to the appropriate profile page
        $user = $request->user();
        if ($user->isAdmin() || $user->isOrganisateur()) {
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } else {
            return Redirect::route('profile.editfront')->with('status', 'profile-updated');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}