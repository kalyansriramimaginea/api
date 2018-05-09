<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\HTTP\V1\Models\Beer;
use Carbon\Carbon as Carbon;
use Maatwebsite\Excel\Facades\Excel as Excel;

class ImportBeers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:beers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import CSV Beer to Beer';

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
		Excel::load($tools->path . 'beers.csv', function($reader) {

			$tools = new ImportTools();
		    $results = $reader->all();

			foreach ($results as $result) {

				$beer = new Beer;

				$beer->beerList = $tools->findObject('beer_list', $result->beer_list);

				$beer->brewerId = $tools->findObject('brewer', $result->brewery_name)->id;
				$beer->flavorId = $tools->findObject('flavor', title_case($result->beer_flavor_profile))->id;
				$beer->beerTypeId = $tools->findObject('beer_type', title_case($result->beer_flavor_profile))->id;

				$beer->name = $tools->exists($result->beer_name, '', 191);
				$beer->abv = $tools->exists(number_format((float)$result->beer_abv, 2, '.', ''), '', 191);
				$beer->ibu = $tools->exists($result->beer_ibu, '', 191);
				$beer->about = $tools->exists($result->beer_description, '');
				$beer->untappd = $tools->exists($result->beer_untappd_id, '', 191);
				$beer->rare = 0;
				$beer->sortOrder = 1000;

				$beer->created_at = Carbon::now()->timestamp;
				$beer->updated_at = Carbon::now()->timestamp;

				$beer->save();

				$this->info($beer->name . ' saved.');
			}

			$this->info($results->count() . ' saved.');

		});

    }

}
