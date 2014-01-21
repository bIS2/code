<?php

class StateObserver {

  public function created($model) {
  	Holding::find($model->holding_id)->update([ 'state' => $model->state ]);

  	//if state==ok delete all annotated state 
  	if ($model->state=='ok' && State::whereHoldingId($model->holding_id)->whereState('annotated' )->exists() )
	  	State::whereHoldingId($model->holding_id)->andWhere( 'state','=','annotated' )->delete();

  	//if state is 'annotated' delete ok state 
		if ($model->state=='annotated' && State::whereHoldingId($model->holding_id)->whereState('ok' )->exists())
	  	State::whereHoldingId($model->holding_id)->andWhere( 'state', '=','ok' )->delete();


	if ( $model->state=='received' ) {

		//find holdings with in same holdingsset 
		$holding_in_set = Holding::whereHoldingssetId( $model->holding->holdingsset_id );

		$owner_in_set = $holding_in_set->whereIsOwner('t')->lists('id');
		$aux_in_set = $holding_in_set->whereIsAux('t')->lists('id');

		$owner_received = $holding_in_set->whereState('received')->lists('id');
		$aux_received = $holding_in_set->whereState('received')->lists('id');

		$count_owner = count($owner_in_set); 								// count owner in holdingsets (HOS)
		$count_owner_received = count($owner_received);			// count owner received in holdingsets (HOS)
		$count_aux = count($aux_in_set);										// count aux in holdingsets (HOS)
		$count_aux_receievd = count($aux_received);					// count aux received in holdingsets (HOS)

		if (( $count_owner == $count_owner_received ) && ($count_aux == $count_aux_received) ){

			$owner_and_aux = array_intersect($owner_in_set,$aux_in_set);
			$no_owner_and_aux = array_diff( $holding_in_set->count(), $owner_and_aux);

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