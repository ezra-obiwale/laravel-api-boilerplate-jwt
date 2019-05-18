<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use Auth;

/**
 * @group Auth
 */
class LogoutController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', []);
    }

    /**
     * Log out
     * 
     * Log the user out (Invalidate the token)
     * @authenticated
     * 
     * @responseFile app/test-responses/auth/logout.json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard()->logout();

        return response()
            ->json(['message' => 'Successfully logged out']);
    }
}
