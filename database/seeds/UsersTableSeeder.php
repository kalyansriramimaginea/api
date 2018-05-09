<?php

use Illuminate\Database\Seeder;
use MongoDB\BSON\UTCDateTime;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		$password = str_random(12);
		$admin = env('API_ADMIN');

		$user = User::where('email', $admin)->first();
		if ($user) {
			echo 'Admin already exists' . PHP_EOL;
			exit;
		}

		$user = new User;
		$user->name = 'Admin';
		$user->email = $admin;
		$user->password = bcrypt($password);
		$user->save();

		Artisan::call('role:create', [
		    'name' => 'admin',
		]);

		Artisan::call('role:add', [
		    'name' => 'admin',
			'email' => $user->email
		]);

		echo 'Admin pass: ' . $password . PHP_EOL;

    }

}
