<?php
/*
* Observer events (created, deleted) that occur on the model Comment
*
*/

class CommentObserver {

  public function creating($model) {

  	// Delete holding from all list
    $holding = Holding::find($model->holding_id)->hlists()->detach();

    //create state commented
    State::create([ 'holding_id' => $model->holding_id, 'user_id'=>Auth::user()->id, 'state' => 'commented' ]);

  }

  public function deleted($model){
  	// Set related holding like NOT delivered
    $ids = $model->hlist->holdings()->select('holdings.id')->lists('id' );
    $ids[] = -1;
    State::whereIn( 'holding_id', $ids )->delete();

  }

}