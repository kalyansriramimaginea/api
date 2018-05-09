<?php

namespace App\Console\Commands;

use App\Http\V1\Models\Event;
use Illuminate\Console\Command;
use Carbon\Carbon as Carbon;
use Maatwebsite\Excel\Facades\Excel as Excel;

class ImportEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Events from Business Section';

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
        $response = file_get_contents('https://downtownop.org/?test2_vforit=1');
        $json = \GuzzleHttp\json_decode($response);
        $count = 0;
        foreach($json as $event) {

            $startAt = Carbon::parse($tools->exists($event->startAt, '', 191))->timestamp;
            if(strlen($event->startAt) == 0 || $startAt < Carbon::now()->timestamp) continue;


            $count++;
            //if($count > 10) continue;






            $newEvent = new Event;


            $newEvent->name = $tools->exists($event->name, '');
            $newEvent->about = $tools->exists(strip_tags($event->about), '');
            $newEvent->venue = $tools->exists($event->venue, '');
            $newEvent->address = $tools->exists($event->address, '');
            $newEvent->photoUrl = $tools->exists($event->photoUrl, '', 191);
            $newEvent->siteUrl = $tools->exists($event->siteUrl, '', 191);
            $newEvent->fbUrl = $tools->exists($event->fbUrl, '', 191);
            $newEvent->twUrl = $tools->exists($event->twUrl, '', 191);
            $newEvent->inUrl = $tools->exists($event->inUrl, '', 191);
            $newEvent->siteUrl = $tools->exists($event->siteUrl, '', 191);
            $newEvent->startAt = Carbon::parse($tools->exists($event->startAt, '', 191))->timestamp;
            $newEvent->endAt = Carbon::parse($tools->exists($event->endAt, '', 191))->timestamp;
            $newEvent->allDay = $tools->exists($event->allDay, '', 191);

            $newEvent->created_at = Carbon::now()->timestamp;
            $newEvent->updated_at = Carbon::now()->timestamp;



            $newEvent->eventCategoryId = $tools->findObject('event_category', 'All Events')->id ;

            $newEvent->save();
            $this->info(Carbon::parse($tools->exists($event->startAt, '', 191)) . ' time.', '<p><br>');

            $this->info('ID: '.$event->ID .' -- '. strip_tags($event->name) . ' food saved.', '<p><br>');
        }
    }

}
