<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\HTTP\V1\Models\Brewer;
use Carbon\Carbon as Carbon;
use Maatwebsite\Excel\Facades\Excel as Excel;

class ImportBrewers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:brewers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import CSV Brewer to Brewer';

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
		Excel::load($tools->path . 'brewers.csv', function($reader) {

			$tools = new ImportTools();
		    $results = $reader->all();

			foreach ($results as $result) {

				$brewer = new Brewer;

				$brewer->name = $tools->exists($result->brewery_name, '', 191);

				$stateCode = $tools->codeForState($result->brewery_state);
				$from = $tools->exists($result->brewery_city, '', 191);

				if ($from != "" && $stateCode != "") {
					$from = $from . ', ' . $stateCode;
				} else if ($stateCode != "") {
					$from = $stateCode;
				}

				$brewer->from = $tools->exists($from, '', 191);
	            $brewer->about = $tools->exists($result->brewery_descriptionbio, '');
				$brewer->photoUrl = '';
				$brewer->iconUrl = '';
				$brewer->siteUrl = $tools->exists($result->brewery_website_url, '', 191);
				$brewer->fbUrl = $tools->exists($result->brewery_facebook_url, '', 191);
				$brewer->twUrl = $tools->exists($result->brewery_twitter_url, '', 191);
				$brewer->inUrl = $tools->exists($result->brewery_instagram_url, '', 191);
				$brewer->twFollowUrl = '';
				$brewer->display = 1;

				$brewer->created_at = Carbon::now()->timestamp;
				$brewer->updated_at = Carbon::now()->timestamp;

				$brewer->save();

				$this->info($brewer->name . ' saved.');
			}

			$this->info($results->count() . ' saved.');

		});

    }

}
