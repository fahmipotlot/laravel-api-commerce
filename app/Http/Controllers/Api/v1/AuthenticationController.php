<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::whereRaw('LOWER(email) = ?', strtolower(request()->email))->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email / Password doesn\'t match our record'
            ], 401);
        }

        $authenticated = \Auth::attempt([
            'email' => $user->email,
            'password' => request()->password
        ]);

        if ($authenticated) {
            $user->tokens()->delete();
            $user = \Auth::user();
            $user->token = $user->createToken('auth_token')->plainTextToken;;

            return $user;
        } else {
            return response()->json([
                'message' => 'Email / Password doesn\'t match our record'
            ], 401);
        }
    }
}
