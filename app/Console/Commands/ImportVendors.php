<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\V1\Models\Vendor;
use Carbon\Carbon as Carbon;
use Maatwebsite\Excel\Facades\Excel as Excel;

class ImportVendors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:vendors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Vendors from Business Section';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tools = new ImportTools();
        $response = file_get_contents('https://downtownop.org/?test2_xcoutme=1');
        $json = \GuzzleHttp\json_decode($response);
        foreach($json as $vendor) {

            $saved = ['food'=>false, 'shopping'=>false, 'service'=>false ];

            foreach($vendor->business_type as $typeTax) {
                if($typeTax->parent == 103 || $typeTax->term_taxonomy_id == 103) { // services ﻿﻿5ae340aefd897860fe7d1916

                    if($saved['service']) continue;
                    $saved['service'] = true;


                    $newVendor = $this->createVendor($vendor);
                    $newVendor->vendorCategoryId = $tools->findObject('vendor_category', 'Services')->id ;

                    $newVendor->save();

                    $this->info($newVendor->name . ' service saved.');

                }
                if($typeTax->parent == 72 || $typeTax->term_taxonomy_id == 72) { // shopping ﻿﻿﻿5ae340a5fd897821136093c8

                    if($saved['shopping']) continue;
                    $saved['shopping'] = true;


                    $newVendor = $this->createVendor($vendor);
                    $newVendor->vendorCategoryId = $tools->findObject('vendor_category', 'Shopping')->id ;

                    $newVendor->save();

                    $this->info($newVendor->name . ' shopping saved.');

                }
                if($typeTax->parent == 71 || $typeTax->term_taxonomy_id == 71) { // food ﻿﻿﻿﻿5ae340aafd89784bf9367ea6

                    if($saved['service']) continue;
                    $saved['service'] = true;


                    $newVendor = $this->createVendor($vendor);
                    $newVendor->vendorCategoryId = $tools->findObject('vendor_category', 'Food & Drink')->id ;

                    $newVendor->save();

                    $this->info($newVendor->name . ' food saved.');
                }
            }
        }
    }

    private function createVendor($vendor) {
        $tools = new ImportTools();

        $newVendor = new Vendor;


        $newVendor->name = $tools->exists($vendor->name, '');
        $newVendor->about = $tools->exists($vendor->about, '');
        $newVendor->address = $tools->exists($vendor->address, '');
        $newVendor->photoUrl = $tools->exists($vendor->photoUrl, '', 191);
        $newVendor->siteUrl = $tools->exists($vendor->siteUrl, '', 191);
        $newVendor->fbUrl = $tools->exists($vendor->fbUrl, '', 191);
        $newVendor->twUrl = $tools->exists($vendor->twUrl, '', 191);
        $newVendor->inUrl = $tools->exists($vendor->inUrl, '', 191);

        $newVendor->created_at = Carbon::now()->timestamp;
        $newVendor->updated_at = Carbon::now()->timestamp;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/geocode/json?address='. rawurlencode($vendor->address) .'&key=AIzaSyAU_4ifEk7I1h2dMKDXGf7sO4eo2LDgag4');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds

        $resp = curl_exec($ch);

        $googleResponse = json_decode($resp);

        // Results can come back with varying precision, we just grab the raw lat/long and pass the results to the json.
        if(count($googleResponse->results) > 0) {
            $newVendor->lat = $googleResponse->results[0]->geometry->location->lat;
            $newVendor->lon = $googleResponse->results[0]->geometry->location->lng;
        }
        return $newVendor;
    }
}
