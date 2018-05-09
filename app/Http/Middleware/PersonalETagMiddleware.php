<?php

namespace App\Http\Middleware;

use Closure;
use Cache;
use Illuminate\Http\Response;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;

class PersonalETagMiddleware {

    /**
     * Implement Etag support
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

		if ($request->isMethod('get') && ($request->path() == 'redirect' || $request->path() == 'callback')) {
			$response = $next($request);
			return $response;
		}

        // IF ETAG EXISTS FOR PATH
		$tag = 'ETag.'.$this->getAuthUserId().'.'.$request->path();

        $localEtag = Cache::get($tag, 'none');
        $requestEtag = str_replace('"', '', $request->getETags());

        if ($request->isMethod('get') && $requestEtag && ($localEtag == $requestEtag[0])) {
            $response = new Response(null);
            $response->setNotModified();
            return $response;
        } else {
            $response = $next($request);
            if ($request->isMethod('get')) {
              $newEtag = md5($response->getContent());
              $response->setEtag($newEtag, true);
              $response->header('Cache-Control', 'max-age=3600,public');
              Cache::forever($tag, $newEtag);
          } else if ($request->isMethod('post') || $request->isMethod('delete')) {
              Cache::forget($tag);
            }
            return $response;
        }

    }

	public function getAuthUserId()
	{

	    try {
	        if (! $user = JWTAuth::parseToken()->authenticate()) {
	            return '';
	        }
	    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
	        return '';
	    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
	        return '';
	    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
	        return '';
	    }

	    return $user->_id;
	}

}
