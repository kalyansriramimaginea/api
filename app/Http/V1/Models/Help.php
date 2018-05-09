<?php

namespace App\Http\V1\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Help extends Eloquent {

	use SoftDeletes;

 	protected $connection = 'mongodb';

 	protected $fillable = ['question', 'answer', 'photoUrl', 'sortOrder'];
    protected $dates = ['deleted_at'];

	protected $casts = [

	];

  	protected function getDateFormat() {
		return 'U';
	}

  	public static function rules() {
		return [
			'question' => 'required',
			'answer' => 'required',
      	];
  	}

	public function publicAttributes() {
		return [
			'question' => $this->question,
			'answer' => $this->answer,
			'photoUrl' => $this->photoUrl,
			'sortOrder' => (int)$this->sortOrder
		];
	}

}
