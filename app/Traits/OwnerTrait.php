<?php

namespace App\Traits;

use Illuminate\Support\Facades\Input;

trait OwnerTrait {

    public function scopeOwner($query, $user, $id, $key = '_id') {

		return $query->where('userId', '=', $user->_id)->where($key, '=', $id);

    }

	public function scopeOwnerAll($query, $user) {

		return $query->where('userId', '=', $user->_id);

    }

	public function scopeChild($query, $object, $key) {

		return $query->where($key, '=', $object->_id);

    }

	public function scopeChildAll($query, $objects, $key) {

		return $query->whereIn($key, $objects->pluck('_id'));

    }

}
