<?php
// error_reporting(E_ALL);
class CleanStatesSeeder extends Seeder {

    public function run() {

    	State::truncate();
    	Hlist::truncate();
    	Confirm::truncate();
    	Incorrect::truncate();
    	DB::table('hlist_holding')->truncate();

    	DB::table('holdings')->update(['state'=>'blank']);
    	DB::table('holdingssets')->update(['state'=>'blank']);

    }
}