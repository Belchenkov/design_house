<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        $token = $this->guard()->attempt($this->credentials($request));

        if (!  $token) {
            return response()->json([
                "errors" => [
                    "credentials" => "Invalid credentials!"
                ]
            ], 401);
        }

        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return response()->json([
                "errors" => [
                    "verification" => "You need to verify your email account!"
                ]
            ], 401);
        }

        return $this->sendLoginResponse($request);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'message' => 'Logout successfully!'
        ]);
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        $token = (string)$this->guard()->getToken();

        // extract the expiry date of the token
        $expiration = $this->guard()->getPayload()->get('exp');

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return response()->json([
                "errors" => [
                    "verification" => "You need to verify your email account!"
                ]
            ], 401);
        }

        throw ValidationException::withMessages([
           $this->username() => "Authentication failed!"
        ]);
    }
}
