<?php

class DatabaseSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();

        // Add calls to Seeders here
        $this->call('UsersTableSeeder');
        $this->call('RolesTableSeeder');
        $this->call('PermissionsTableSeeder');
		$this->call('LibrariesTableSeeder');
		$this->call('GroupsTableSeeder');
		$this->call('TagsTableSeeder');
		$this->call('TracesTableSeeder');
		$this->call('DeliveriesTableSeeder');
		$this->call('ConfirmsTableSeeder');
<<<<<<< HEAD
<<<<<<< HEAD
		$this->call('LockedsTableSeeder');
=======
		$this->call('RevisedsTableSeeder');
>>>>>>> 86da6699d295af34ca3376881e7d69712455ac23
=======
		$this->call('RevisedsTableSeeder');
		$this->call('LockedsTableSeeder');
>>>>>>> 244bf5e537375d6bff2d7e12b5a44c93e8936705
	}

}