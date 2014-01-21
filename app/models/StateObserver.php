<?php

class StateObserver {

  public function created($model) {
  	Holding::find($model->holding_id)->update([ 'state' => $model->state ]);

  	//if state==ok delete all annotated state 
  	if ($model->state=='ok' && State::whereHoldingId($model->holding_id)->whereState('annotated' )->exists() )
	  	State::whereHoldingId($model->holding_id)->andWhereState('annotated' )->delete();

  	//if state is 'annotated' delete ok state 
	if ($model->state=='annotated' && State::whereHoldingId($model->holding_id)->whereState('ok' )->exists())
	  	State::whereHoldingId($model->holding_id)->andWhereState('ok' )->delete();

  	// si el holding recibido es owner se integran todos los holdings pertenecientes al mismo holdingsset
	if ( $model->state=='received' && $model->holding->is_owner ){
		//find holdings with in same holdingsset 
		$ids = Holding::whereHoldingssetId( $model->holding->holdingsset_id )->whereState('received')->lists('id');
		foreach ($ids as $holding_id) {
			State::create( [ 'holding_id'=>$holding_id, 'user_id'=>Auth::user()->id, 'state'=>'integrated' ] );
		}	  
	}

	if ( $model->state=='received' &&  Holding::whereHoldingssetId( $model->holding->holdingsset_id )->whereState('received')->whereOwner('t')->exists ){
		State::create( [ 'holding_id'=>$model->holding_id, 'user_id'=>Auth::user()->id, 'state'=>'integrated' ] );
	}

  }


}