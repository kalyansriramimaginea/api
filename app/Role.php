<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Zizaco\Entrust\Traits\EntrustRoleTrait;

class Role extends Eloquent
{
	use EntrustRoleTrait;

	public function users()
    {
		return $this->belongsToMany('App\User');
    }

}
