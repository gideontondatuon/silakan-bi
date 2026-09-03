<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $updateData = [
            'password' => Hash::make($validated['password']),
        ];

        if ($request->user()->role->value !== 'admin') {
            $updateData['password_plain'] = $validated['password'];
        }

        $request->user()->update($updateData);

        return back()->with('status', 'password-updated');
    }
}
