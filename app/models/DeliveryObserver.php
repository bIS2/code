<?php

class DeliveryObserver {

  public function created($model) {

  	$holding = Holding::find($model->holding_id);
  	// if ($holding->is_annotated)      
  	// 	Confirm::whereHoldingssetId( $holding->holdingsset_id )->delete();
      // Holdingsset::find($holding->holdingsset_id)->update(['state' => 'blank']);

  	// Set related holding like delivered
  	//Holding::whereIn( 'holdings.id',$model->hlist->holdings()->select('holdings.id')->lists('id') );
    $ids = $model->hlist->holdings()->whereState('revised_ok')->select('holdings.id')->lists('id');
    $lockeds = array();
    foreach ( $ids as $id ) {
      $state = new State;
      $state->holding_id = $id;
      $state->user_id    = Auth::user()->id;
      $state->state      = 'delivery';
      $state->save();
      if (!in_array($id, $lockeds)) Holdingsset::find(Holding::find($id)->holdingsset_id)->update(['locked' => 1]);
      $lockeds[] = $id;
    }

  }

  public function deleted($model){
  	// Set related holding like NOT delivered
    State::whereIn( 'holding_id', $model->hlist->holdings()->select('holdings.id')->lists('id' ) )->delete();

  }

}