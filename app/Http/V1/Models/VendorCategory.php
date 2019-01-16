<?php

namespace App\Http\V1\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class VendorCategory extends Eloquent {

	use SoftDeletes;

 	protected $connection = 'mongodb';

 	protected $fillable = ['name', 'photoUrl', 'sortOrder'];
    protected $dates = ['deleted_at'];

	protected $casts = [

	];

  	protected function getDateFormat() {
		return 'U';
	}

  	public static function rules() {
		return [
      	];
  	}

	public function publicAttributes() {
		return [
			'name' => $this->name,
			'photoUrl' => str_replace('http://', 'https://s3.amazonaws.com/', $this->photoUrl),
			'sortOrder' => (int)$this->sortOrder
		];
	}

}
