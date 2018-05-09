<?php

namespace App\Http\V1\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller as Controller;

use App;
use Storage;
use Validator;

class ApiFileController extends Controller {

	public function __construct() {

	}

	public function postFile(Request $request) {
		$url = $this->saveFileUrl($request, env('APP_BUCKET') . '/');
		return $url;
	}

	public function saveFileUrl(Request $request, $path) {

		if(!$request->hasFile('file'))
			return response()->json(['error' => 'No File Sent.'], 500);

		if(!$request->file('file')->isValid())
			return response()->json(['error' => 'File is not valid.'], 500);

		$file = $request->file('file');

		$v = Validator::make(
			$request->all(),
			['file' => 'required|mimes:png,jpeg,jpg|max:99999']
		);

		if($v->fails())
			return response()->json(['error' => $v->errors()->first()], 500);

		$filename = (string)bin2hex(openssl_random_pseudo_bytes(12)) . '.' . $file->getClientOriginalExtension();
		$fullname = $path . $filename;

		Storage::disk('s3')->put($fullname, file_get_contents($file));

		return 'http://' . env('AWS_S3_BUCKET') . '/' . $fullname;
	}

}
