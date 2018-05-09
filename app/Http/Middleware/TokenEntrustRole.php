<?php

namespace App\Http\Middleware;

use Closure;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class TokenEntrustRole extends BaseMiddleware
{

    public function handle($request, Closure $next, $role)
    {

        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return response()->json(['error' => 'token_not_provided'], 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
			return response()->json(['error' => 'token_expired'], 404);
        } catch (JWTException $e) {
            return response()->json(['error' => 'token_invalid'], 401);
        }

        if (! $user) {
			return response()->json(['error' => 'user_not_found'], 404);
        }

       	if (!$user->hasRole(explode('|', $role))) {
			return response()->json(['error' => 'token_invalid'], 401);
        }

        app('events')->fire('tymon.jwt.valid', $user);

        return $next($request);
    }

}
