<?php
/*
* Observer events (created, deleted) occur Confirm model. Perform necessary actions after a specific event occurs on the model.
*
*/
class ConfirmObserver {

    public function created($model) {

    	$user_id = Auth::user()->id;
      $holdingsset_id = $model->holdingsset->id;
      Trace::create([ 
       'user_id' => $user_id,
       'action'  => trans("logs.confirmed_as_ok"),
       'object_type' => 'holdingsset',
       'object_id' => $holdingsset_id,
      ]);

      Holdingsset::find($holdingsset_id)->update([ 'state' => 'ok' ]);
      $ids = $model->holdingsset->holdings()->lists('id');
      foreach ($ids as $id) {
	      State::create( [ 'holding_id' => $id, 'user_id' => $user_id, 'state'=>'confirmed' ] );
      }
    }

    public function deleted($model) {

    	$user_id = Auth::user()->id;
    	$holdingsset_id = $model->holdingsset->id;

      Trace::create([ 
       'user_id' => $user_id,
       'action'  => trans("logs.unconfirmed_as_ok"),
       'object_type' => 'holdingsset',
       'object_id' => $holdingsset_id,
      ]);
      $ids = $model->holdingsset->holdings()->lists('id');
      foreach ($ids as $id) {
        State::create( [ 'holding_id' => $id, 'user_id' => $user_id, 'state'=>'blank' ] );
      }

      Holdingsset::find($holdingsset_id)
      ->update([ 
       'state' => 'blank',
       'locked' => '0'
      ]);
      Holding::whereHoldingsset_id($holdingsset_id)->where('state', 'NOT LIKE ', '%annotated%')->update([ 
       'state' => 'blank'
      ]);
    }
}