<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Installation extends Eloquent {

 	protected $connection = 'mongodb';

 	protected $fillable = ['deviceToken', 'appId', 'contactEmail', 'contactAlias', 'appVersion', 'device', 'deviceName', 'deviceVersion', 'channels', 'timeZone'];
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
		];
	}

}
