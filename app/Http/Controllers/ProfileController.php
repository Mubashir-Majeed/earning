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

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                // Check both public and storage paths
                $oldPathPublic = public_path('images/profile-pictures/' . basename($user->profile_picture));
                $oldPathStorage = storage_path('app/public/' . $user->profile_picture);
                if (file_exists($oldPathPublic)) {
                    @unlink($oldPathPublic);
                }
                if (file_exists($oldPathStorage)) {
                    @unlink($oldPathStorage);
                }
            }

            // Create directory if it doesn't exist
            $uploadDir = public_path('images/profile-pictures');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $file = $request->file('profile_picture');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Store in storage first for backup
            $storagePath = $file->storeAs('profile-pictures', $filename, 'public');
            
            // Copy to public folder
            $publicPath = $uploadDir . '/' . $filename;
            copy(storage_path('app/public/' . $storagePath), $publicPath);
            
            // Save public path in database
            $validated['profile_picture'] = 'images/profile-pictures/' . $filename;
        } else {
            // Keep existing profile picture if not uploading new one
            unset($validated['profile_picture']);
        }

        $user->fill($validated);
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
