<?php

namespace App\Http\Controllers\V1\Auth;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Entities\V1\User;
use App\Entities\V1\Role;
use Auth;
use App\Http\Controllers\Controller;
use App;

class SocialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['guest', 'web']);
    }

    public function redirectToProvider($provider) {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        $request->validate([
            'token' => 'required'
        ]);

        $user = Socialite::driver($provider)->stateless()->userFromToken($request->token);
        $userCheck = User::where('email', '=', $user->email)->first();
        $email = $user->email;

        if (!$user->email) {
            $email = 'missing' . str_random(10);
        }
        if (!empty($userCheck)) {
            $socialUser = $userCheck;
        } else {
            $socialUser = User::create([
                'full_name' => $user->name,
                'email' => $email,
                'password' => bcrypt(str_random(16)),
                'token' => str_random(64),
                'slug' => $this->newSlug($user->name),
                'avatar' => $user->getAvatar()
            ]);
            $socialUser->confirmEmail();
            $socialUser->save();
        }
        if (!$socialUser->roles()->count()) {
            $socialUser->roles()->attach(Role::where('name', 'user')->first()->id);
        }

        $token = Auth::login($socialUser, true);
        $expires = Auth::guard()->factory()->getTTL() * 60;

        return [
            'status' => 'ok',
            'data' => [
                'token' => $token,
                'expires' => $expires
            ]
        ];
    }

    /**
     * Check if a slug exists in the user database.
     *
     * @param  variable  $data
     * @return boolean
     */
    private function slugExist($slug)
    {
        return (User::where('slug', $slug)->first()) ? true : false;
    }

    /**
     * Generate unique slug.
     *
     * @param  variable  $data
     * @return $data
     */
    private function newSlug($name)
    {
        $i =1;
        $slug = str_slug($name);
        $baseSlug = $slug;
        while ($this->slugExist($slug)) {
            $slug = $baseSlug . "-" . $i++;
        }
        return $slug;
    }
}
