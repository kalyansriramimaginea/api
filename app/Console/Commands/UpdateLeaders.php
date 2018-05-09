<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Leader;
use App\User;
use App\Http\V1\Models\Contest;
use App\Http\V1\UserModels\UserChallenge;
use Carbon\Carbon as Carbon;

class UpdateLeaders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:leaders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update leader scores.';

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
		$time = time();

        $contests = Contest::get()->sortBy('startAt');
		foreach ($contests as $cont) {

			if ((int)$cont->startAt < $time &&
				(int)$cont->endAt > $time) {
				$contest = $cont;
				break;
			}

        }

		$leaders = collect([]);
		if (isset($contest)) {

			$userChallenges = UserChallenge::where('contestId', $contest->_id)->get()->sortBy('userId');
			$lastId = "";
			foreach ($userChallenges as $userChallenge) {
				if ($lastId != $userChallenge->userId) {
					$leaders->push(["userId" => $userChallenge->userId, "contestId" => $userChallenge->contestId, "score" => $userChallenge->score]);
				} else {
					$leader = $leaders->pop();
					$leader["score"] = $leader['score'] + $userChallenge->score;
					$leaders->push($leader);
				}
				$lastId = $userChallenge->userId;
			}

			$leaders = $leaders->sortByDesc('score')->values();
			$leadLimits = $leaders->slice(0, 20);

			$newleaders = collect([]);
			foreach ($leadLimits as $i => $leadLimit) {
				$user = User::where('_id', $leadLimit["userId"])->first();
				$leader = $leaders->shift();
				$leader["username"] = $user->username;
				$leader["sortOrder"] = $i + 1;
				$leader["updated_at"] = Carbon::now()->timestamp;
				$leader["created_at"] = Carbon::now()->timestamp;
				$newleaders->push($leader);
			}

			Leader::where("contestId", $contest->_id)->delete();
			DB::table('leaders')->insert($newleaders->all());

			$this->info('success.');

		}

    }

}
