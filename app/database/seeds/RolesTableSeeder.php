<?php

class RolesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();
        $datetime = new DateTime;
        $roles = [
        	['name'=> 'superuser', 'created_at' => $datetime, 'updated_at' => $datetime ],
        	['name'=> 'resuser', 'created_at' => $datetime, 'updated_at' => $datetime ],
        	['name'=> 'bibuser', 'created_at' => $datetime, 'updated_at' => $datetime],
        	['name'=> 'maguser', 'created_at' => $datetime, 'updated_at' => $datetime],
        	['name'=> 'magvuser', 'created_at' => $datetime, 'updated_at' => $datetime],
        	['name'=> 'postuser', 'created_at' => $datetime, 'updated_at' => $datetime],
        	['name'=> 'speiuser', 'created_at' => $datetime, 'updated_at' => $datetime],
        ];

        DB::table('roles')->insert( $roles );

        $adminRole = Role::where('name','=','speiuser')->first();
        $user = User::where('username','=','admin')->first();

		$user->attachRole( $adminRole );

    }

}
