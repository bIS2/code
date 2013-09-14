<?php

class CreateUsersTable extends Ruckusing_Migration_Base
{
    public function up()
    {
        $users = $this->create_table("users");
        $users->column("first_name", "string");
        $users->column("last_name", "string"); 
        $users->finish();       
    }//up()

    public function down()
    {
         $this->drop_table("users");
    }//down()
}
