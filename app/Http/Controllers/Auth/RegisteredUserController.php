<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referrer_id' => ['nullable', 'string', 'exists:users,referral_code'],
        ]);

        $referrer = null;
        $referralCode = $request->referrer_id ?: $request->ref;
        
        if ($referralCode) {
            $referrer = User::where('referral_code', $referralCode)->first();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referrer_id' => $referrer?->id,
            'level' => 1, // Default to level 1
        ]);

        // Award bonus points to new user if they have a referrer
        if ($referrer) {
            $user->increment('points', 100); // 100 bonus points for using referral
            $user->increment('balance', 0.10); // $0.10 bonus
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
