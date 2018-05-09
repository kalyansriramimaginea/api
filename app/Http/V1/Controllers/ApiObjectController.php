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

class ApiObjectController extends Controller {

	public function __construct() {

	}

	public function getCollection($className) {

    	$singular = str_singular($className);
    	$class = "App\Http\V1\Models\\".studly_case($singular);
    	$all = $class::all();

		$cursor = self::getCursor($all);

		return $this->response->collection($all, new App\Transformers\ApiObjectTransformer, ['key' => strtolower($singular)], function ($resource, $fractal) use ($cursor) {
    		$resource->setCursor($cursor);
			$fractal->setSerializer(new JsonApiSerializer);
		});
	}

	public function postObject(Request $request, $className) {

		$singular = str_singular($className);
		$class = "App\Http\V1\Models\\".studly_case($singular);

		$this->validate($request, $class::rules());

		if ($request->has("id")) {
			$object = $class::find($request->id);
			if (!$object) {
				return response()->json(['error' => 'Object does not exist.'], 412);
			}
		} else {
			$object = new $class;
		}

		$object->fill($request->all());
		$object->save();

		Cache::forget('ETag.'.$request->path());

		return $this->response->item($object, new App\Transformers\ApiObjectTransformer, ['key' => strtolower($singular)], function ($resource, $fractal) {
			$fractal->setSerializer(new JsonApiSerializer);
		});
 	}

	public function deleteObject(Request $request, $className) {

		$singular = str_singular($className);
		$class = "App\Http\V1\Models\\".studly_case($singular);

		if ($request->has("id")) {
			$object = $class::find($request->id);
			if ($object) {
				$object->delete();
				Cache::forget('ETag.'.$request->path());
			}
		}

	}

}
