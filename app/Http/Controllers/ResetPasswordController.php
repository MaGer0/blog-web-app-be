<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink([
            'email' => $validated['email']
        ]);

        return $status === Password::ResetLinkSent
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $status = Password::reset(
            [
                'email' => $validated['email'],
                'password' => $validated['password'],
                'password_confirmation' => $validated['password_confirmation'],
                'token' => $validated['token']
            ],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
            }
        );

        return $status === Password::PasswordReset
            ? response()->json(['message' => __($status)])
            : response()->json(['message' => __($status)], 400);
    }
}
