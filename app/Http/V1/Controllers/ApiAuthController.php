<?php

namespace App\Http\V1\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller as Controller;
// use App\Transformers\UserTransformer;
use App\Installation;
use App\User;
use App\Permission;
use App\Role;
use Config;

class ApiAuthController extends Controller {

	public function __construct() {

	}

	public function register(Request $request) {


		$uniqueCount = User::where('email', $request->email)->count();

		if ($uniqueCount > 0) {
			return response()->json(['error' => 'User already exists.'], 412);
		}

		// ANONYMOUS REGISTER
		if ($request->has("anonymousId") && $request->anonymousId != "") {

          $user = $this->getAuthUser();

          if ($user->anonymousId == $request->anonymousId &&
		  	  $request->email != Config::get('api.admin') &&
			  $request->email != '') {

			$password = Config::get('api.defaultPassword');

			$user->anonymousId = $user->anonymousId;
            $user->name = $request->name;
            $user->email = $request->email;
			$user->password = bcrypt($password);
            $user->receiveNews = $request->receiveNews;
			$user->accountCreated = true;
            $user->save();

			$credentials = ['email' => $request->email, 'password' => $password];

          } else {
  			return response()->json(['error' => 'Could not create user. Please try again.'], 502);
  		  }

		// NORMAL REGISTER
	  	} else if ($request->has("email")) {

			$user = new User;
			$user->name = $request->name;
			$user->email = $request->email;
			$user->password = bcrypt($request->password);
            $user->receiveNews = $request->receiveNews;
			$user->accountCreated = false;
			$user->save();

			$credentials = ['email' => $request->email, 'password' => $request->password];

		} else {
			return response()->json(['error' => 'Could not create user. Please try again.'], 503);
		}

		try {
			if (! $token = JWTAuth::attempt($credentials)) {
			 	return response()->json(['error' => 'Invalid Credentials.'], 401);
			}
		} catch (JWTException $e) {
			return response()->json(['error' => 'Could not create user. Please try again.'], 520);
		}

		return response()->json(compact('token'));
	}

    public function anonymous(Request $request) {

        if ($request->has("anonymousId") && $request->anonymousId != "") {

			$uniqueCount = User::where('anonymousId', $request->anonymousId)->count();

	        if ($uniqueCount > 0) {
				return response()->json(['error' => 'Could not create user. Please try again.'], 510);
			}

			$user = new User;
			$password = Config::get('api.defaultPassword');
			$username = "";//$this->createUsername();

			$user->anonymousId = $request->anonymousId;
			$user->name = 'Anonymous';
			$user->username = $username;
			$user->email = $request->anonymousId;
			$user->password = bcrypt($password);
            $user->receiveNews = false;
			$user->accountCreated = false;

			$user->save();

			$credentials = ['email' => $request->anonymousId, 'password' => $password];

			try {
				if (! $token = JWTAuth::attempt($credentials)) {
				  return response()->json(['error' => 'Invalid Credentials.'], 401);
				}
			} catch (JWTException $e) {
				return response()->json(['error' => 'Could not create user. Please try again.'], 510);
			}

			return response()->json(compact('token'));

		}

		return response()->json(['error' => 'Could not create user. Please try again.'], 500);

    }

    public function login(Request $request) {

        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function logout() {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response('', 200);
    }

	public function install(Request $request) {

        if ($request->has("deviceToken")) {
			$installation = Installation::where('deviceToken', $request->deviceToken)->first();
			if (!$installation) {
				$installation = new Installation;
			}
		} else {
			$installation = new Installation;
		}

		$installation->fill($request->all());
		$installation->save();

        return response('', 200);
	}

	public function getUser() {

	    try {
	        if (! $user = JWTAuth::parseToken()->authenticate()) {
	            return response()->json(['user_not_found'], 404);
	        } else {

                var_dump('TEST');
                var_dump($user->_id);
            }
	    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
	        return response()->json(['token_expired'], $e->getStatusCode());
	    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
	        return response()->json(['token_invalid'], $e->getStatusCode());
	    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

	    return response()->json(compact('user'));
	}

    public function getUsername() {

        $user = $this->getAuthUser();

        $username = $this->createUsername();
        $user->username = $username;
        $user->save();

        return response()->json(compact('username'));

    }
    public function getEmail() {

        $user = $this->getAuthUser();

        return response()->json(compact('username'));

    }

    function createUsername() {

        $username = $this->generateRandomUsername();

        $uniqueCount = User::where('username', $username)->count();

        if ($uniqueCount == 0) {
            return $username;
        } else {
            return $this->createUsername();
        }

    }

    function generateRandomUsername() {

        $firstNames = [""];
        $middleNames = [""];
        $lastNames = [""];

        $f1 = mt_rand(0, count($firstNames) - 1);
        $m1 = mt_rand(0, count($middleNames) - 1);
        $l1 = mt_rand(0, count($lastNames) - 1);

        return $firstNames[$f1] . " " . $middleNames[$m1] . " " . $lastNames[$l1];

    }

}
