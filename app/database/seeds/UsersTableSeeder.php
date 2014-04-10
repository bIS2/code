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
            ),
            array(
                'username'      	=> 'librarian',
                'email'      		=> 'librarian@example.org',
                'password'   		=> Hash::make('librarian'),
                'confirmed'   		=> 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'created_at' 		=> new DateTime,
                'updated_at' 		=> new DateTime,
            ),
            array(
                'username'      	=> 'storeman',
                'email'      		=> 'storeman@example.org',
                'password'   		=> Hash::make('storeman'),
                'confirmed'   		=> 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'created_at' 		=> new DateTime,
                'updated_at' 		=> new DateTime,
            ),
            array(
                'username'          => 'worker',
                'email'             => 'storeman@example.org',
                'password'          => Hash::make('storeman'),
                'confirmed'         => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'created_at'        => new DateTime,
                'updated_at'        => new DateTime,
            ),
            array(
                'username'          => 'postuser',
                'email'             => 'postuser@example.org',
                'password'          => Hash::make('postuser'),
                'confirmed'         => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'created_at'        => new DateTime,
                'updated_at'        => new DateTime,
            ),
            array(
                'username'          => 'speichuser',
                'email'             => 'speichuser@example.org',
                'password'          => Hash::make('speichuser'),
                'confirmed'         => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'created_at'        => new DateTime,
                'updated_at'        => new DateTime,
            )

        );

        DB::table('users')->insert( $users );
    }

}
