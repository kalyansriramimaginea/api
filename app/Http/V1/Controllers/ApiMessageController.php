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
use App\Helpers\UA as UA;

class ApiMessageController extends Controller {

	public function __construct() {

	}

	public function getCollection() {

    	$singular = str_singular('message');
    	$class = "App\\".studly_case($singular);
    	$all = $class::all();

		$cursor = self::getCursor($all);

		return $this->response->collection($all, new App\Transformers\ApiObjectTransformer, ['key' => strtolower($singular)], function ($resource, $fractal) use ($cursor) {
    		$resource->setCursor($cursor);
			$fractal->setSerializer(new JsonApiSerializer);
		});
	}

	public function postObject(Request $request) {

		$this->validate($request, App\Message::rules());

		if ($request->has("id")) {
			$object = App\Message::find($request->id);
			if (!$object) {
				return response()->json(['error' => 'Object does not exist.'], 412);
			}
		} else {
			$object = new App\Message;
		}

		if ($object->sendAt) {
			$originalSendAt = $object->sendAt;
		} else {
			$originalSendAt = $request->sendAt;
		}
		$object->fill($request->all());

		if (!$request->has("id") && $request->push == 1) {

			$object->uaId = UA::update("", $request, false);

		} else if ($object->uaId != "") {

			if ($request->push == 0) {
				UA::delete($object->uaId, $originalSendAt);
			  	$object->uaId = "";
			} else {
				$object->uaId = UA::update($object->uaId, $request, false);
			}

		} else {
			if ($request->kind != "push") {
				$object->uaId = UA::update("", $request, true);
			}
		}
		if($request->kind != "group-push") {
            $object->save();
        }

		Cache::forget('ETag.'.$request->path());

		return $this->response->item($object, new App\Transformers\ApiObjectTransformer, ['key' => 'message'], function ($resource, $fractal) {
			$fractal->setSerializer(new JsonApiSerializer);
		});
	}

	public function deleteObject(Request $request) {

		if ($request->has("id")) {
			$object = App\Message::find($request->id);
			if ($object && $object->uaId != "") {
				UA::delete($object->uaId, $object->sendAt);
		    }
			if ($object) {
				$object->delete();
				Cache::forget('ETag.'.$request->path());
			}
		}

	}


}
