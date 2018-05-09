<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Input;
use Tymon\JWTAuth\Exceptions\JWTException;
use League\Fractal\Pagination\Cursor;
use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Routing\Helpers;
use JWTAuth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

	protected function throwValidationException(\Illuminate\Http\Request $request, $validator) {
		throw new ValidationHttpException($validator->errors());
  	}

	public function getAuthUser()
	{

	    try {
	        if (! $user = JWTAuth::parseToken()->authenticate()) {
	            return response()->json(['user_not_found'], 404);
	        }
	    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
	        return response()->json(['token_expired'], $e->getStatusCode());
	    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
	        return response()->json(['token_invalid'], $e->getStatusCode());
	    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
	        return response()->json(['token_absent'], $e->getStatusCode());
	    }

	    return $user;
	}

    public function getCursor($items)
    {
        if ($items->count() > 0) {
            $nextCursor = $items->last()->_id;
        } else {
            $nextCursor = null;
        }

        return new Cursor(Input::get('cursor', null), Input::get('previous', null), $nextCursor, $items->count());
    }

}
