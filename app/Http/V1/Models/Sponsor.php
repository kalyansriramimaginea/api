<?php

namespace App\Http\V1\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Sponsor extends Eloquent {

	use SoftDeletes;

 	protected $connection = 'mongodb';

 	protected $fillable = ['tierId', 'name', 'siteUrl', 'photoUrl', 'sortOrder'];
    protected $dates = ['deleted_at'];

	protected $casts = [

	];

  	protected function getDateFormat() {
		return 'U';
	}

  	public static function rules() {
		return [
			'tierId' => 'required',
			'name' => 'required',
			'photoUrl' => 'required',
			'sortOrder' => 'required'
      	];
  	}

	public function publicAttributes() {
		return [
			'tierId' => $this->tierId,
			'name' => $this->name,
			'siteUrl' => $this->siteUrl,
			'photoUrl' => str_replace('http://', 'https://s3.amazonaws.com/', $this->photoUrl),
			'sortOrder' => (int)$this->sortOrder
		];
	}

}
