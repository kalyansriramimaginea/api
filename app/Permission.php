<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Zizaco\Entrust\Traits\EntrustPermissionTrait;

class Permission extends Eloquent
{
	use EntrustPermissionTrait;

	public function roles()
	{
		return $this->hasMany('App\Role');
	}

}
