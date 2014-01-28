<?php

class StateObserver {

  public function created($model) {
  	
  	Holding::find($model->holding_id)->update([ 'state' => $model->state ]);

  	if ($model->state == 'revised_annotated') Confirm::whereHoldingssetId(Holding::find($model->holding_id)->holdingsset_id)->delete();

  	//if state==ok delete all annotated state 
  // 	if ($model->state=='ok' && State::whereHoldingId($model->holding_id)->whereState('annotated' )->exists() )
	 //  	State::whereHoldingId($model->holding_id)->andWhere( 'state','=','annotated' )->delete();

  // 	//if state is 'annotated' delete ok state 
		// if ($model->state=='annotated' && State::whereHoldingId($model->holding_id)->whereState('ok' )->exists())
	 //  	State::whereHoldingId($model->holding_id)->andWhere( 'state', '=','ok' )->delete();


	if ( $model->state=='received' ) {

		//find holdings with in same holdingsset 
		$holding_in_set = Holding::whereHoldingssetId( $model->holding->holdingsset_id );

		$owner_received = $holding_in_set->whereIsOwner('t')->whereState('received')->lists('id');

		if (( count($owner_received)>0 )){

			$aux_in_set = $holding_in_set->whereIsAux('t')->lists('id');
			$aux_received = $holding_in_set->whereIsAux('t')->whereState('received')->lists('id');

			if ( count($aux_in_set) == count($aux_received))  {

				$owner_and_aux = array_merge( $owner_in_set,$aux_in_set );
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