<?php

namespace App\Http\Middleware;

use Closure;
use Cache;
use Illuminate\Http\Response;

class ETagMiddleware {

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
        $localEtag = Cache::get('ETag.'.$request->path(), 'none');
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
              Cache::forever('ETag.'.$request->path(), $newEtag);
          } else if ($request->isMethod('post') || $request->isMethod('delete')) {
              Cache::forget('ETag.'.$request->path());
            }
            return $response;
        }

    }

}
