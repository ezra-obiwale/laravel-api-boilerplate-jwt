<?php

namespace App\Http\Controllers\V1\Auth;

use Config;
use App\Entities\V1\User;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SignUpRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Auth;

/**
 * @group Auth
 */
class SignUpController extends Controller
{
    /**
     * Sign up
     * 
     * Register a new user account
     * 
     * @bodyParam first_name string required The first name of the user
     * @bodyParam last_name string required The last name of the user
     * @bodyParam email string required The email address of the user
     * @bodyParam password string required The password to the user account
     * 
     * @responseFile app/test-responses/auth/signup.json
     *
     * @param SignUpRequest $request
     * @param JWTAuth $JWTAuth
     * @return void
     */
    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {
        $user = new User($request->all());
        if(!$user->save()) {
            throw new HttpException(500);
        }

        if(!Config::get('boilerplate.sign_up.release_token')) {
            return response()->json([
                'status' => 'ok'
            ], 201);
        }

        $token = $JWTAuth->fromUser($user);
        return response()->json([
            'status' => 'ok',
            'token' => $token,
            'expires_in' => Auth::guard()->factory()->getTTL() * 60
        ], 201);
    }
}
