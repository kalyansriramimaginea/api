<?php

namespace App\Http\V1\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Event extends Eloquent {

	use SoftDeletes;

 	protected $connection = 'mongodb';

 	protected $fillable = ['name', 'eventCategoryId', 'photoUrl', 'sponsorPhotoUrl', 'sponsorUrl', 'about', 'venue', 'address', 'fbUrl', 'twUrl', 'inUrl', 'siteUrl', 'startAt', 'lat', 'lon', 'endAt', 'allDay'];
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
			'photoUrl' => str_replace('http://', 'https://s3.amazonaws.com/', $this->photoUrl),
			'sponsorPhotoUrl' => str_replace('http://', 'https://s3.amazonaws.com/', $this->sponsorPhotoUrl),
			'sponsorUrl' => $this->sponsorUrl,
            'about' => $this->about,
            'address' => $this->address,
			'fbUrl' => $this->fbUrl,
			'twUrl' => $this->twUrl,
			'inUrl' => $this->inUrl,
			'siteUrl' => $this->siteUrl,
			'startAt' => (int)$this->startAt,
			'endAt' => (int)$this->endAt,
			'lat' => $this->lat,
			'lon' => $this->lon,
			'allDay' => $this->allDay
		];
	}

}
