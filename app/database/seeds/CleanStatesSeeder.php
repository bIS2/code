<?php
// error_reporting(E_ALL);
class CleanStatesSeeder extends Seeder {

    public function run() {

    	DB::table('confirms')->delete();
    	DB::table('comments')->delete();
    	DB::table('deliveries')->delete();
    	DB::table('feedbacks')->delete();
    	DB::table('group_holdingsset')->delete();
    	DB::table('groups')->delete();
    	DB::table('hlist_holding')->delete();
    	DB::table('hlists')->delete();
    	DB::table('incorrects')->delete();
    	DB::table('lockeds')->delete();
    	DB::table('notes')->delete();
    	DB::table('receiveds')->delete();
    	DB::table('reserves')->delete();
    	DB::table('reviseds')->delete();
    	DB::table('states')->delete();
    	DB::table('traces')->delete();

    	DB::table('holdings')->update(['state'=>'blank']);
    	DB::table('holdingssets')->update(['state'=>'blank', 'groups_number' => 0]);

    }
}