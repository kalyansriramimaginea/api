<?php

namespace App\Http\V1\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class MenuSlide extends Eloquent {

	use SoftDeletes;

 	protected $connection = 'mongodb';

 	protected $fillable = ['title', 'about', 'url', 'sortOrder'];
    protected $dates = ['deleted_at'];

	protected $casts = [

	];

  	protected function getDateFormat() {
		return 'U';
	}

  	public static function rules() {
		return [
			'title' => 'required',
			'sortOrder' => 'required'
      	];
  	}

	public function publicAttributes() {
		return [
			'title' => $this->title,
			'about' => $this->about,
			'url' => $this->url,
			'sortOrder' => (int)$this->sortOrder
		];
	}

}
