<?php

class ConfirmObserver {

    public function created($model) {

    	$user_id = Auth::user()->id;

      Trace::create([ 
       'user_id' => $user_id,
       'action'  => trans("logs.confirmed_as_ok"),
       'object_type' => 'holdingsset',
       'object_id' => $model->holdingsset->id,
      ]);

      foreach ($model->holdingsset->holdings()->lists('id') as $id) {
	      State::create( [ 'holding_id' => $id, 'user_id' => $user_id, 'state'=>'confirmed' ] );
      }
    }

    public function deleted($model) {

    	$user_id = Auth::user()->id;
    	
      foreach ($model->holdingsset->holdings()->lists('id') as $id) {
	      State::create( [ 'holding_id' => $id, 'user_id' => $user_id, 'state'=>'blank' ] );
      }

      Trace::create([ 
       'user_id' => $user_id,
       'action'  => trans("logs.unconfirmed_as_ok"),
       'object_type' => 'holdingsset',
       'object_id' => $model->holdingsset->id,
      ]);

    }
}