<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Eloquent implements AuthenticatableContract, CanResetPasswordContract, JWTSubject
{
    use Authenticatable, CanResetPassword, Notifiable, EntrustUserTrait;

	protected $connection = 'mongodb';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	protected $dates = ['deleted_at'];

	protected $attributes = [

	];

	protected $casts = [
		'receiveNews' => 'integer',
		'accountCreated' => 'integer'
	];

  	protected function getDateFormat() {
		return 'U';
	}

  	public static function rules() {
		return [
			'email' => 'sparse'
      	];
  	}

	public function getJWTIdentifier()
	{
	    return $this->getKey();
	}

	public function getJWTCustomClaims()
	{
		$roles = [];
		foreach ($this->roles as $role) {
			$roles[] = $role->name;
		}
	    return ["id" => $this->_id,
                "username" => $this->username,
                "roles" => implode(",", $roles)];
	}

}
