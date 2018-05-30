<?php

namespace App\Helpers;

use App\Installation;
use Log;
use UrbanAirship\Airship;
use UrbanAirship\AirshipException;
use UrbanAirship\UALog;
use UrbanAirship\Push;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class UA
{

	public static function update($uaId, $request, $silent) {

		$message = $request->message;
		$sendAt = $request->sendAt;
		$channels = $request->channels;
		$deepLink = $request->deepLink;
		$kind = $request->kind;

		$tags = explode(',', $channels);
		if ($deepLink == '') {
			$deepLink = env('APP_BUCKET') . "://inbox";
		}

		$audience = array("tag" => $tags);
		if ($channels == '') {
			$audience = Push\all;
		}
        if(!empty($request->get('targets'))) {
            var_dump('test');
            $audience = [];

            foreach($request->get('targets') as $target) {
                $installations = Installation::where('contactEmail', $target['filter-email']);
                foreach($installations as $install) {
                    var_dump($install);
                    if($install->device == 'ios') {
                        if(!isset($audience['device_token'])) {
                            $audience['device_token'] = [];
                        }
                        $audience['device_token'][] = $install->deviceToken;
                    } else {
                        if(!isset($audience['android_channel'])) {
                            $audience['android_channel'] = [];
                        }
                        $audience['android_channel'][] = $install->deviceToken;
                    }
                }
            }
        }
        var_dump($audience);

		exit();

	    $airship = new Airship(Config::get('airship.airshipKey'), Config::get('airship.airshipSecret'));

		try {

	        $options = array();
	        $overrides = array();
			$message = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $message);
			$message = mb_substr($message, 0, 230);

			if ($silent == false) {

		        $push = $airship->push()
		                            ->setAudience($audience)
									// ->setAudience(Push\all)
		                            ->setDeviceTypes(Push\deviceTypes("ios", "android"))
		                            ->setNotification(Push\notification(null, array("ios" => Push\ios($message, "+1", null, true, array("kind" => $kind, "deep" => $deepLink)), "android" => Push\android($message, null, null, null, false, array("kind" => $kind, "deep" => $deepLink)))));

			} else {

				$push = $airship->push()
		                            ->setAudience($audience)
									// ->setAudience(Push\all)
		                            ->setDeviceTypes(Push\deviceTypes("ios", "android"))
		                            ->setNotification(Push\notification(null, array("ios" => Push\ios(null, null, null, true, array("kind" => $kind, "deep" => $deepLink)), "android" => Push\android(null, null, null, null, false, array("kind" => $kind, "deep" => $deepLink)))));
				Log::info(json_encode($push->getPayload()));
			}
	                            // ->setInApp($options);

	        // NOT SCHEDULED
	        if ($sendAt < time()) {
	          if ($uaId == "") {
	            $response = $push->send();
	          }
	          return "";
	        } else {
	          if ($uaId == "") {
	              $response = $airship->scheduledPush()
	                                  ->setPush($push)
	                                  ->setSchedule(Push\scheduledTime($sendAt))
	                                  ->send();
	              return $response->payload["schedule_ids"][0];
	          } else {
	            $response = $airship->scheduledPush()
	                                ->setPush($push)
	                                ->setSchedule(Push\scheduledTime($sendAt))
	                                ->update($uaId);
	            return "";
	          }

	        }

	    } catch (AirshipException $e) {
	      Log::info($e->details);
	      die;
	    }

	}

	public static function delete($uaId, $sendAt) {

	    $airship = new Airship(Config::get('airship.airshipKey'), Config::get('airship.airshipSecret'));

		if ($sendAt > time()) {
		    try {
				$client = new Client();
				$res = $client->delete('https://go.urbanairship.com/api/schedules/' . $uaId, [
				    'auth' => [Config::get('airship.airshipKey'), Config::get('airship.airshipSecret')],
					'headers' => [
				        'Accept' => 'application/vnd.urbanairship+json; version=3;'
					]
				]);
				Log::info('Deleted ' . $res->getStatusCode());
		    } catch (AirshipException $e) {
		      Log::info($e);
		      die;
		    }
		}

	    return;

	}

}
