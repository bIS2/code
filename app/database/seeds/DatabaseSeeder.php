<?php

class DatabaseSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();

	    // Add calls to Seeders here
	    //$this->call('UsersTableSeeder');
	    //$this->call('RolesTableSeeder');
	    //$this->call('PermissionsTableSeeder');
			//$this->call('LibrariesTableSeeder');
			$this->call('GroupsTableSeeder');
			$this->call('TagsTableSeeder');
			$this->call('DeliveriesTableSeeder');
			$this->call('ConfirmsTableSeeder');
			$this->call('RevisedsTableSeeder');
			$this->call('LockedsTableSeeder');
			$this->call('FeedbacksTableSeeder');
			$this->call('IncorrectsTableSeeder');
			$this->call('ReceivedsTableSeeder');
		$this->call('CommentsTableSeeder');
	}

}