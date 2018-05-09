<?php

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use App\Role;
use App\Permission;
use App\User;
use Carbon\Carbon;

Artisan::command('db:backup', function () {

	$fileName = env('APP_BUCKET').'-'.Carbon::now()->timestamp;
	$process = new Process('./backup.sh ' . $fileName . ' ' . env('DB_PORT') . ' ' . env('DB_DATABASE') . ' ' . env('DB_USERNAME') . ' ' . env('DB_PASSWORD'));
	$process->run();

	$fileName = $fileName . '.tar.gz';

	Storage::disk('backups')->put($fileName, Storage::get($fileName));
	Storage::delete($fileName);

	$this->info('Backup complete');

})->describe('Create a db backup');

Artisan::command('role:create {name}', function ($name) {

    $role = new Role();
    $role->name = $name;
    $role->save();
	$this->info("Role " . $name . " created.");

})->describe('Create a role');

Artisan::command('role:add {name} {email}', function ($name, $email) {

	$user = User::where('email', '=', $email)->first();
    $role = Role::where('name', '=', $name)->first();

	if ($role && $user) {
		$user->attachRole($role);
		$user->save();
		$this->info("Role " . $name . " added to " . $email);
	} else {
		$this->comment("Role or user does not exist");
	}

})->describe('Add a role to the user');

Artisan::command('role:remove {name} {email}', function ($name, $email) {

	$user = User::where('email', '=', $email)->first();
    $role = Role::where('name', '=', $name)->first();

	if ($role && $user) {
		$user->detachRole($role);
		$user->save();
		$this->info("Role " . $name . " removed from " . $email);
	} else {
		$this->comment("Role or user does not exist");
	}

})->describe('Remove a role from the user');

Artisan::command('role:delete {name}', function ($name) {

    $role = Role::where('name', '=', $name)->first();

	if ($role) {

		$users = User::where('role_id', '=', $role->_id)->get();
		foreach ($users as $user) {
	    	$user->detachRole($role);
			$this->info("User " . $name . " deleted");
	    }

		$role->users()->delete();
		$role->forceDelete();
		$this->info("Role " . $name . " deleted");
	} else {
		$this->comment("Role does not exist");
	}

})->describe('Delete a role from all users');
