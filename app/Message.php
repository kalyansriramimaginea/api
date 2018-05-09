<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Message extends Eloquent {

 	protected $connection = 'mongodb';

 	protected $fillable = ['uaId', 'messageType', 'kind', 'channels', 'message', 'photoUrl', 'push', 'deepLink', 'sendAt'];
    protected $dates = ['deleted_at'];

	protected $casts = [

	];

  	protected function getDateFormat() {
		return 'U';
	}

  	public static function rules() {
		return [
			'uaId' => '',
			'messageType' => 'required',
			'kind' => 'required',
			'channels' => '',
			'message' => 'required',
			'photoUrl' => '',
			'push' => 'required|integer',
			'deepLink' => '',
			'sendAt' => 'required|integer',
      	];
  	}

	public function publicAttributes() {
		return [
			'uaId' => $this->uaId,
			'messageType' => $this->messageType,
			'kind' => $this->kind,
			'channels' => $this->channels,
			'message' => $this->message,
			'photoUrl' => $this->photoUrl,
			'push' => $this->push,
			'deepLink' => $this->deepLink,
			'sendAt' => (int)$this->sendAt
		];
	}

}
