<?php

namespace App\Http\V1\UserModels;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Traits\OwnerTrait;

class TestUserObject extends Eloquent {

	use OwnerTrait;

 	protected $connection = 'mongodb';

 	protected $fillable = ['user_id', 'object_id', 'queued'];

	protected $casts = [
		'queued' => 'integer'
	];

  	protected function getDateFormat() {
		return 'U';
	}

  	public static function rules() {
		return [
			'object_id' => 'required',
			'queued' => 'required',
      	];
  	}

	public function publicAttributes() {
		return [
			'object_id' => $this->object_id,
			'queued' => $this->queued
		];
	}

}
