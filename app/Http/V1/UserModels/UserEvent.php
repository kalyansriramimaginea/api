<?php

namespace App\Http\V1\UserModels;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Traits\OwnerTrait;

class UserEvent extends Eloquent {

	use OwnerTrait;

 	protected $connection = 'mongodb';

 	protected $fillable = ['localId', 'userId', 'eventId', 'wishlist'];

	protected $casts = [
		'wishlist' => 'integer'
	];

  	protected function getDateFormat() {
		return 'U';
	}

  	public static function rules() {
		return [
			'eventId' => 'required',
			'wishlist' => 'required',
      	];
  	}

	public function publicAttributes() {
		return [
			'userId' => $this->userId,
			'localId' => $this->localId,
			'eventId' => $this->eventId,
			'wishlist' => (int)$this->wishlist
		];
	}

}
