<?php

namespace App\Http\V1\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Tier extends Eloquent {

	use SoftDeletes;

 	protected $connection = 'mongodb';

 	protected $fillable = ['name', 'sortOrder'];
    protected $dates = ['deleted_at'];

	protected $casts = [

	];

  	protected function getDateFormat() {
		return 'U';
	}

  	public static function rules() {
		return [
			'name' => 'required',
			'sortOrder' => 'required'
      	];
  	}

	public function publicAttributes() {
		return [
			'name' => $this->name,
			'sortOrder' => (int)$this->sortOrder
		];
	}

}
