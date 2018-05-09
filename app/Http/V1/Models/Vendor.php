<?php

namespace App\Http\V1\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Vendor extends Eloquent {

    use SoftDeletes;

    protected $connection = 'mongodb';

    protected $fillable = ['vendorCategoryId', 'name', 'about', 'address', 'photoUrl', 'siteUrl', 'fbUrl', 'twUrl', 'inUrl', 'lat', 'lon', 'sortOrder'];
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
            'vendorCategoryId' => $this->vendorCategoryId,
            'name' => $this->name,
            'about' => $this->about,
            'address' => $this->address,
            'photoUrl' => $this->photoUrl,
            'siteUrl' => $this->siteUrl,
            'fbUrl' => $this->fbUrl,
            'twUrl' => $this->twUrl,
            'inUrl' => $this->inUrl,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'sortOrder' => (int)$this->sortOrder
        ];
    }

}
