<?php

namespace App\Http\V1\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use League\Fractal\Serializer\JsonApiSerializer;
use App\Http\Controllers\Controller as Controller;

use App;
use Cache;

class ApiUserObjectController extends Controller {

	public function __construct() {

	}

	public function getCollection($className) {

    	$singular = str_singular($className);
    	$class = "App\Http\V1\UserModels\\".studly_case($singular);

		$user = $this->getAuthUser();
		$all = $class::ownerAll($user)->get();
		$cursor = self::getCursor($all);

		return $this->response->collection($all, new App\Transformers\ApiObjectTransformer, ['key' => strtolower($singular)], function ($resource, $fractal) use ($cursor) {
    		$resource->setCursor($cursor);
			$fractal->setSerializer(new JsonApiSerializer);
		});
	}

	public function postObject(Request $request, $className) {

		$singular = str_singular($className);
		$class = "App\Http\V1\UserModels\\".studly_case($singular);

		$this->validate($request, $class::rules());

		$user = $this->getAuthUser();

		if ($request->has("id")) {
			$object = $class::owner($user, $request->id)->first();
			if (!$object) {
				return response()->json(['error' => 'Object does not exist.'], 412);
			}
		} else {
			$object = new $class;
		}

		$object->fill($request->all());
		$object->userId = $user->_id;
		$object->save();

		Cache::forget('ETag.'.$user->_id.'.'.$request->path());

		return $this->response->item($object, new App\Transformers\ApiObjectTransformer, ['key' => strtolower($singular)], function ($resource, $fractal) {
			$fractal->setSerializer(new JsonApiSerializer);
		});
 	}

	public function deleteObject(Request $request, $className) {

		$singular = str_singular($className);
		$class = "App\Http\V1\UserModels\\".studly_case($singular);

		$user = $this->getAuthUser();

		if ($request->has("id")) {
			$object = $class::owner($user, $request->id)->first();
			if ($object) {
				$object->delete();
				Cache::forget('ETag.'.$user->_id.'.'.$request->path());
			}
		}

	}

}
