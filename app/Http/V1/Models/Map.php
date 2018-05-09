<?php

namespace App\Http\V1\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Map extends Eloquent {

	use SoftDeletes;

 	protected $connection = 'mongodb';

 	protected $fillable = ['name', 'photoUrl', 'lat', 'lon', 'zoom', 'sortOrder'];
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
			'photoUrl' => $this->photoUrl,
			'lat' => $this->lat,
			'lon' => $this->lon,
			'zoom' => (int)$this->zoom,
			'sortOrder' => (int)$this->sortOrder
		];
	}

}
