<?php

class PermissionsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('permissions')->delete();


        $permissions = array(
            array(
                'name'      	=> 'create_list_holgroups',
                'display_name'  => 'Create list of Holgroups'
            ),
            array(
                'name'      	=> 'reserve',
                'display_name'  => 'Reserve Hol'
            ),
            array(
                'name'      	=> 'edit_hol',
                'display_name'  => 'Edit Hol'
            ),
            array(
                'name'      	=> 'admin',
                'display_name'  => 'Manage User and Roles'
            ),
        );

        DB::table('permissions')->insert( $permissions );

        $adminRole = Role::where('name','=','speiuser')->first();
        $adminPermission = Permission::where('name','=','admin')->first();

        DB::table('permission_role')->insert( [ 'permission_id' => $adminPermission->id, 'role_id'=> $adminRole->id] );

    }

}
