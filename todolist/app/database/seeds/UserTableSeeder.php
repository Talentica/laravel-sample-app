<?php

class UserTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->delete();

		User::create(array(
		'username' => 'piyush',
		'password' => Hash::make('piyush')
		));

		User::create(array(
		'username' => 'subhrojit',
		'password' => Hash::make('subhrojit')
		));
	}

}