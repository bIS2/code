<?php

class DatabaseSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();

        // Add calls to Seeders here
        $this->call('UsersTableSeeder');
        $this->call('RolesTableSeeder');
        $this->call('PermissionsTableSeeder');
		$this->call('HolgroupsTableSeeder');
		$this->call('LibrariesTableSeeder');
		$this->call('ReservesTableSeeder');
		$this->call('GroupsTableSeeder');
		$this->call('CabinetsTableSeeder');
		$this->call('CommentsTableSeeder');
		$this->call('Comments_categoriesTableSeeder');
		$this->call('ListsTableSeeder');
		$this->call('TagsTableSeeder');
	}

}