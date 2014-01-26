<?php

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

      Holdingsset::find($holdingsset_id)
      ->update([ 
       'state' => 'ok'
      ]);

      foreach ($model->holdingsset->holdings()->lists('id') as $id) {
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

      foreach ($model->holdingsset->holdings()->lists('id') as $id) {
        State::create( [ 'holding_id' => $id, 'user_id' => $user_id, 'state'=>'blank' ] );
      }

      Holdingsset::find($holdingsset_id)
      ->update([ 
       'state' => 'blank'
      ]);

    }
}