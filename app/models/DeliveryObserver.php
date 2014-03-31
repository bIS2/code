<?php
/*
* Observer events (created, deleted) occur Delivery model. Perform necessary actions after a specific event occurs on the model.
*
*/
class DeliveryObserver {

  public function created($model) {

  	$holding = Holding::find($model->holding_id);

  	// Set related holding like delivered
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
    $ids = $model->hlist->holdings()->select('holdings.id')->lists('id' );
    $ids[] = -1;
    State::whereIn( 'holding_id',  )->delete();

  }

}