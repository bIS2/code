<?php

class UsersTableSeeder extends Seeder {

    public function run()
    {
 		DB::table('permission_role')->delete();        
 		DB::table('assigned_roles')->delete();
        DB::table('users')->delete();

        $users = array(
            array(
                'username'      	=> 'admin',
                'email'      		=> 'admin@example.org',
                'password'   		=> Hash::make('admin'),
                'confirmed'   		=> 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'created_at' 		=> new DateTime,
                'updated_at' 		=> new DateTime,
            )
        );

        DB::table('users')->insert( $users );
    }

}
