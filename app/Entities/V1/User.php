<?php

namespace App\Entities\V1;

use Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Auth;
use Mail;
use App\Mail\ConfirmationAccount;
use Illuminate\Notifications\Notifiable;
use TCG\Voyager\Models\User as TCGUser;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends TCGUser implements JWTSubject
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'photo', 'city', 'country', 'slug', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'token'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'verified_at' => 'datetime:Y-m-d'
    ];

    protected $withArray = [
        'is_active', 'verified_at'
    ];

    /**
     *
     * Boot the model.
     *
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($builder) {
            $builder->with('roles');
        });

        static::creating(function ($user) {
            $user->token = str_random(40);
            
            $username = preg_replace('/[^a-z0-9]/', '', strtolower($user->name));
            if (strlen($username) > 15) {
                $username = substr($username, 0, 15);
            }
                
            $user->username = $username;
        });
    }

    /**
     * Automatically creates hash for the user password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Confirm the user.
     *
     * @return void
     */
    public function confirmEmail()
    {
        $this->verified_at = now();
        $this->token = null;

        $this->save();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Fetches the appropriate slug for a user
     *
     * @return string
     */
    public function slug()
    {
        return $this->username ? : $this->slug;
    }

    public function isAdmin()
    {
        return $this->roles()->where('name', 'admin')->count() > 0;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['slug'] = $this->slug();
        return $array;
    }

    public function sendRegistrationEmail()
    {
        Mail::to($this->email)->send(new ConfirmationAccount($this));
    }
}
