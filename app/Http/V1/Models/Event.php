<?php

namespace App\Http\V1\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Event extends Eloquent {

	use SoftDeletes;

 	protected $connection = 'mongodb';

 	protected $fillable = ['name', 'eventCategoryId', 'photoUrl', 'sponsorPhotoUrl', 'about', 'venue', 'address', 'fbUrl', 'twUrl', 'inUrl', 'siteUrl', 'startAt', 'endAt', 'allDay'];
    protected $dates = ['deleted_at'];

	protected $casts = [

	];

  	protected function getDateFormat() {
		return 'U';
	}

  	public static function rules() {
		return [
			'name' => 'required'
      	];
  	}

	public function publicAttributes() {
		return [
            'name' => $this->name,
			'eventCategoryId' => $this->eventCategoryId,
            'venue' => $this->venue,
			'photoUrl' => $this->photoUrl,
			'sponsorPhotoUrl' => $this->sponsorPhotoUrl,
            'about' => $this->about,
            'address' => $this->address,
			'fbUrl' => $this->fbUrl,
			'twUrl' => $this->twUrl,
			'inUrl' => $this->inUrl,
			'siteUrl' => $this->siteUrl,
			'startAt' => (int)$this->startAt,
			'endAt' => (int)$this->endAt,
			'allDay' => $this->allDay
		];
	}

}
