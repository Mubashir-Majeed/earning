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
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if (array_key_exists('bep20_address', $validated)) {
            $submittedAddress = $validated['bep20_address'];

            if (empty($submittedAddress)) {
                unset($validated['bep20_address']);
            } else {
                $normalizedAddress = strtolower($submittedAddress);

                if ($user->hasBoundWallet()) {
                    if (strcasecmp($user->bep20_address, $normalizedAddress) !== 0) {
                        return Redirect::route('profile.edit')
                            ->withErrors(['bep20_address' => 'Your wallet is already bound. Contact support to update it.'])
                            ->withInput();
                    }

                    unset($validated['bep20_address']);
                } else {
                    $validated['bep20_address'] = $normalizedAddress;
                    $validated['wallet_bound_at'] = now();
                    $validated['payment_method'] = 'bep20';
                    $validated['payment_details'] = $normalizedAddress;
                }
            }
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
