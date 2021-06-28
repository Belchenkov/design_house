<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use http\Env\Response;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request, User $user)
    {
        // check if the url is a valid signed url
        if (! URL::hasValidSignature($request)) {
            return response()->json([
               "errors" => [
                   "message" => "Invalid verification link"
               ],
               422
            ]);
        }

        // check if the user has already verified account
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                "errors" => [
                    "message" => "Email address already verified"
                ],
                422
            ]);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json([
            "message" => "Email successfully verified"
        ], 200);
    }

    public function resend(Request $request)
    {
        $this->validate($request, [
            'email' => [
                'email',
                'required'
            ]
        ]);

        if (! $user = User::where('email', $request->email)->first()) {
            return response()->json([
                'errors' => [
                    'email' => 'No user could be found with email address'
                ]
            ],422);
        }

        // check if the user has already verified account
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                "errors" => [
                    "message" => "Email address already verified"
                ],
                422
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'status' => 'Verification link resent'
        ]);
    }
}
