<?php

namespace App\Http\V1\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;
use App\Http\Controllers\Controller as Controller;

use App;
use Cache;

class ApiLeaderController extends Controller {

	public function __construct() {

	}

	public function getCollection() {

    	$singular = str_singular('leaders');
    	$class = "App\\".studly_case($singular);
    	$all = $class::filterable()->get();

		$cursor = self::getCursor($all);

		return $this->response->collection($all, new App\Transformers\ApiObjectTransformer, ['key' => strtolower($singular)], function ($resource, $fractal) use ($cursor) {
    		$resource->setCursor($cursor);
			$fractal->setSerializer(new JsonApiSerializer);
		});
	}

}
