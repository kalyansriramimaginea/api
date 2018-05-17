<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'api', 'namespace' => 'App\Http\V1\Controllers'], function ($api) {

	// CREATE NEW USER
    $api->post('users/installation', 'ApiAuthController@install');
    $api->post('users/anonymous', 'ApiAuthController@anonymous');
    $api->post('users/register', 'ApiAuthController@register');
	$api->post('users/login', 'ApiAuthController@login');

	// ADMIN ONLY
	$api->group(['middleware' => ['jwt.auth', 'roles:admin']], function ($api) {

		$api->post('classes/files', 'ApiFileController@postFile');
		$api->post('classes/message', 'ApiMessageController@postObject');
		$api->delete('classes/message', 'ApiMessageController@deleteObject');

		$api->post('classes/{className}', 'ApiObjectController@postObject');
		$api->delete('classes/{className}', 'ApiObjectController@deleteObject');

	});

	// PUBLIC LOGGED IN
	$api->group(['middleware' => ['jwt.auth']], function ($api) {

		// PUBLIC
        $api->group(['middleware' => ['etag']], function ($api) {

			$api->get('classes/message', 'ApiMessageController@getCollection');
			$api->get('classes/{className}', 'ApiObjectController@getCollection');

		});

		// PERSONAL
		$api->get('users/me', 'ApiAuthController@getUser');
        $api->get('users/email', 'ApiAuthController@getEmail');
		$api->post('users/logout', 'ApiAuthController@logout');

		$api->group(['middleware' => ['personaletag']], function ($api) {

			$api->get('classes/user/{className}', 'ApiUserObjectController@getCollection');
			$api->post('classes/user/{className}', 'ApiUserObjectController@postObject');
			$api->delete('classes/user/{className}', 'ApiUserObjectController@deleteObject');

		});

	});

});
