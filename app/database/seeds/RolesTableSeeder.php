<?php

class RolesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();
        $datetime = new DateTime;
        $roles = [
        	['name'=> 'sysadmin', 'description' => 'System Administrator' , 'created_at' => $datetime, 'updated_at' => $datetime ],
        	['name'=> 'superuser',  'description' => 'Super user' , 'created_at' => $datetime, 'updated_at' => $datetime ],
        	['name'=> 'bibuser', 'description' => 'Librarian' , 'created_at' => $datetime, 'updated_at' => $datetime],
        	['name'=> 'resuser', 'description' => 'Librarian that decides over the retention of Holdings' , 'created_at' => $datetime, 'updated_at' => $datetime],
        	['name'=> 'magvuser', 'description' => 'Storage manager' , 'created_at' => $datetime, 'updated_at' => $datetime],
        	['name'=> 'maguser', 'description' => 'Storage worker' , 'created_at' => $datetime, 'updated_at' => $datetime],
        	['name'=> 'postuser', 'description' => 'Post office worker ' , 'created_at' => $datetime, 'updated_at' => $datetime],
        	['name'=> 'speichuser', 'description' => 'Collaborator of the storage library' , 'created_at' => $datetime, 'updated_at' => $datetime],
        ];

        DB::table('roles')->insert( $roles );

        $adminRole = Role::where('name','=','speiuser')->first();
        $user = User::where('username','=','admin')->first();

		$user->attachRole( $adminRole );

    }

}
