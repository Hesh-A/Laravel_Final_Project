<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Notifications\ProfileUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {

        $user = Auth::user();
        $originalEmail = $user->email;
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => ['nullable', Password::defaults()],
        ]);

        $user->update([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => $attributes['password'] ?? $user->password,
        ]);

        if ($originalEmail !== $attributes['email']) {
            Notification::route('mail', $originalEmail)
                ->notify(new ProfileUpdatedNotification($user, $originalEmail));
        }

        return redirect()->route('idea.index')->with('success', 'Profile updated successfully!');
    }
}
