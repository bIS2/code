<?php

class SetLibtoUserLibrarian extends Seeder {

    public function run()
    {

        $librarian = User::where('username','=','librarian')->first();
        $library = Library::where('code','=','AGKB')->first();
        $librarian->library_id = $library->id;
        
        $librarian->save(); 
    }
}

