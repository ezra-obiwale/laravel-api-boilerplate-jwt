<?php

namespace App\Http\Controllers\V1;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Requests\V1\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;
use App\Entities\V1\User;

class UserController extends SuperController
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

    protected function model() {
        return User::class;
    }

    protected function validationRules(array $data, $id = null) {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'photo' => 'file',
            'username' => 'unique',
        ];
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::guard()->user());
    }
}
