<?php
/*
* Observer events (created, deleted) occur Group model. Perform necessary actions after a specific event occurs on the model.
*
*/
class StateObserver {

  public function created($model) {

	// Whenever a state is created the brand holding is related  	
  	Holding::find($model->holding_id)->update([ 'state' => $model->state ]);

  	// 
  	if ($model->state == 'revised_annotated') {
  		$holdingsset_id = Holding::find($model->holding_id)->holdingsset_id;
  		//Confirm::whereHoldingssetId( Holding::find($holdingsset_id) )->delete();	
  		Holdingsset::find($holdingsset_id)->update(['state' => 'blank']);
  		//DB::table('hlist_holding')->whereHoldingId($model->holding_id)->delete();	
  	}

		if  ( $model->state=='ok' ){

			// if holding has notes then delete state annotates and notes. Decrement couter of annotated stat

			State::whereState('annotated')->whereHoldingId($model->holding_id)->delete();
			Note::whereHoldingId($model->holding_id)->delete();
				
		}

		if  ( $model->state=='annotated' ){
			State::whereState('ok')->whereHoldingId($model->holding_id)->delete();
		}

		if ( $model->state=='received' || $model->state=='commented' ) {
			$model->holding->hlists->each( function($list) {
				$list->check_received();
			});
		}

		if ( $model->state=='received' ) {

			//find holdings with in same holdingsset 
			$holding_in_set = Holding::whereHoldingssetId( $model->holding->holdingsset_id );

			$owner_received = $holding_in_set->whereIsOwner('t')->whereState('received')->lists('id');

			if (( count($owner_received)>0 )){

				$holding_in_set = Holding::whereHoldingssetId( $model->holding->holdingsset_id );
				$aux_in_set = $holding_in_set->whereIsAux('t')->lists('id');


				$holding_in_set = Holding::whereHoldingssetId( $model->holding->holdingsset_id );
				$aux_received = $holding_in_set->whereIsAux('t')->whereState('received')->lists('id');

				if ( count($aux_in_set) == count($aux_received))  {

					$owner_and_aux = array_merge( $owner_received ,$aux_in_set );
					$holding_in_set = Holding::whereHoldingssetId( $model->holding->holdingsset_id );
					$no_owner_and_aux = array_diff( $holding_in_set->lists('id'), $owner_and_aux);

					$model->holding->holdingsset->update(['state'=>'integrated']);

					foreach ($owner_and_aux as $holding_id) {
						State::create( [ 'holding_id'=>$holding_id, 'user_id'=>Auth::user()->id, 'state'=>'integrated' ] );
					}	  

					foreach ($no_owner_and_aux as $holding_id) {
						State::create( [ 'holding_id'=>$holding_id, 'user_id'=>Auth::user()->id, 'state'=>'spare' ] );
					}	  

				}

			}

		}

	}


}