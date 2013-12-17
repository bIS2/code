<?php

class SetGroupAndHolNumberToHoldingssetsSeeder extends Seeder {
    public function run() {

    	$holdingssets = DB::table('holdingssets')->orderBy('id', 'ASC')->get();;
			foreach ( $holdingssets as $holdingsset) {
				$id = $holdingsset->id;
				$holdings_numbers = count(DB::select('select id from holdings where holdingsset_id = ?',[$id]));
				$groups_numbers = count(DB::select('select id from group_holdingsset where holdingsset_id = ?',[$id]));
				// $holdingsset->holdings_numbers = $holdings_numbers; 
				// $holdingsset->groups_numbers = $groups_numbers; 
				print $id.':';
				print $groups_numbers;
				print '-';
				print $holdings_numbers;
				print ' ... ';
				DB::table('holdingssets')
            ->where('id', $id)
            ->update(array('groups_number' => $groups_numbers, 'holdings_number' => $holdings_numbers));
			}
    }
}