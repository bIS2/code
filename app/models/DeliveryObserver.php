<?php

class DeliveryObserver {

  public function created($model) {

  	$holding = Holding::find($model->holding_id);
  	if ($holding->is_annotated)
  		Confirm::whereHoldingssetId( $holding->holdingsset_id )->delete();

  	// Set related holding like delivered
  	//Holding::whereIn( 'holdings.id',$model->hlist->holdings()->select('holdings.id')->lists('id') );
    $ids = $model->hlist->holdings()->select('holdings.id')->lists('id');

    foreach ( $ids as $id ) {
      $state = new State;
      $state->holding_id = $id;
      $state->user_id    = Auth::user()->id;
      $state->state      = 'delivery';
      $state->save();
    }

  }

  public function deleted($model){
  	// Set related holding like NOT delivered
    State::whereIn( 'holding_id', $model->hlist->holdings()->select('holdings.id')->lists('id' ) )->delete();

  }

}