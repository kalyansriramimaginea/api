<?php

namespace App\Console\Commands;

use App\Http\V1\Models\Event;
use App\Http\V1\Models\EventCategory;
use App\Http\V1\Models\VendorCategory;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon as Carbon;
use Storage;
use App\HTTP\V1\Models\Brewer;
use App\HTTP\V1\Models\Flavor;
use App\HTTP\V1\Models\BeerType;

class ImportTools
{

	public $path = '/Users/bret/Desktop/brewers/';

    function check($date) {

        $max = Carbon::createFromTimestampUTC(2147465647);
        $min = Carbon::createFromTimestampUTC(0);

        if ($max->lt($date)) {
            return $max;
        } else if ($min->gt($date)) {
            return $min;
        } else {
            return $date;
        }

    }

    function exists($object, $default, $truncate = null) {

		if ($object) {
			if ($truncate) {
                return substr(trim($object), 0, $truncate);
            } else {
                return trim($object);
            }
		} else {
			return $default;
		}

    }

    function existsBool($array, $key, $default) {

        if (array_key_exists($key, $array)) {
            return $array[$key] == true ? 1 : 0;
        } else {
            return $default;
        }

    }

    function findObject($type, $id) {

        switch ($type) {
            case "beer_list":
				switch (trim($id)) {
					case 'All':
						return 'all';
						break;
					case 'Taps & Tastes':
						return 'taps';
						break;
					default:
						return 'cellar';
						break;
				}
            case "brewer":
                return Brewer::where('name', trim($id))->first();
                break;
            case "event_category":
                return EventCategory::where('name', trim($id))->first();
                break;
            case "vendor_category":
                return VendorCategory::where('name', trim($id))->first();
                break;
			case "flavor":
                return Flavor::where('name', trim($id))->first();
                break;
			case "beer_type":
				$name = '';
				switch (trim($id)) {
					case 'Crisp':
						$name = 'Pale Ale | Ipa | Iipa';
						break;
					case 'Hop':
						$name = 'English | Irish | Scottish';
						break;
					case 'Malt':
						$name = 'German Style';
						break;
					case 'Roast':
						$name = 'Brown | Porter | Stout';
						break;
					case 'Smoke':
						$name = 'Sour | Wild';
						break;
					case 'Fruit & Spice':
						$name = 'Belgian Strong Ale';
						break;
					case 'Tart & Funky':
						$name = 'Saison | Farmhouse';
						break;
					default:
						break;
				}
                return BeerType::where('name', $name)->first();
                break;
            default:
                return null;
        }

    }

    function urlForType($type, $userId, $id) {

        switch ($type) {
            default:
                return 'dtop/' . $userId . '/'. $type . '/' . $id . '/';
        }
    }

    function savePhotoUrl($from, $to) {

		try {
			$img = Image::make($from)->encode('jpg');
		} catch (Intervention\Image\Exception\NotReadableException $e) {
			return '';
		}

		if(!$img)
			return '';

		$filename = (string)bin2hex(openssl_random_pseudo_bytes(12)) . '.jpg';
		$fullname = $to . $filename;

		Storage::disk('s3')->put($fullname, $img->getEncoded());

		return 'http://' . env('AWS_S3_BUCKET') . '/' . $fullname;
	}

	function codeForState($object) {

		$states = array(
		    'AL'=>'Alabama',
		    'AK'=>'Alaska',
		    'AZ'=>'Arizona',
		    'AR'=>'Arkansas',
		    'CA'=>'California',
		    'CO'=>'Colorado',
		    'CT'=>'Connecticut',
		    'DE'=>'Delaware',
		    'DC'=>'District of Columbia',
		    'FL'=>'Florida',
		    'GA'=>'Georgia',
		    'HI'=>'Hawaii',
		    'ID'=>'Idaho',
		    'IL'=>'Illinois',
		    'IN'=>'Indiana',
		    'IA'=>'Iowa',
		    'KS'=>'Kansas',
		    'KY'=>'Kentucky',
		    'LA'=>'Louisiana',
		    'ME'=>'Maine',
		    'MD'=>'Maryland',
		    'MA'=>'Massachusetts',
		    'MI'=>'Michigan',
		    'MN'=>'Minnesota',
		    'MS'=>'Mississippi',
		    'MO'=>'Missouri',
		    'MT'=>'Montana',
		    'NE'=>'Nebraska',
		    'NV'=>'Nevada',
		    'NH'=>'New Hampshire',
		    'NJ'=>'New Jersey',
		    'NM'=>'New Mexico',
		    'NY'=>'New York',
		    'NC'=>'North Carolina',
		    'ND'=>'North Dakota',
		    'OH'=>'Ohio',
		    'OK'=>'Oklahoma',
		    'OR'=>'Oregon',
		    'PA'=>'Pennsylvania',
		    'RI'=>'Rhode Island',
		    'SC'=>'South Carolina',
		    'SD'=>'South Dakota',
		    'TN'=>'Tennessee',
		    'TX'=>'Texas',
		    'UT'=>'Utah',
		    'VT'=>'Vermont',
		    'VA'=>'Virginia',
		    'WA'=>'Washington',
		    'WV'=>'West Virginia',
		    'WI'=>'Wisconsin',
		    'WY'=>'Wyoming',
		);

		foreach ($states as $code => $state) {
			if ($state == $object) {
				return $code;
			}
		}

		return "";

	}

}
