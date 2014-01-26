<?php
// error_reporting(E_ALL);
class CleanStatesSeeder extends Seeder {

    public function run() {

    	DB::table('states')->truncate();
    	DB::table('hlists')->truncate();
    	DB::table('hlist_holding')->truncate();
    	DB::table('confirms')->truncate();
    	DB::table('incorrects')->truncate();

    	DB::table('holdings')->update(['state'=>'blank']);
    	DB::table('holdingssets')->update(['state'=>'blank']);

    }
}