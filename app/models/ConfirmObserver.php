<?php

class ConfirmObserver {

    public function created($model) {

      Trace::create([ 
       'user_id' => Auth::user()->id,
       'action'  => trans("logs.confirmed_as_ok"),
       'object_type' => 'holdingsset',
       'object_id' => $model->holdingsset->id,
      ]);

      foreach ($model->holdings()->lists('id') as $id) {
	      State::create( [ 'holding_id' => $id, 'user_id' => Auth::user()->id, 'state'=>'confirmed' ] );
      }
    }

    public function deleted($model) {

      foreach ($model->holdings()->lists('id') as $id) {
	      State::create( [ 'holding_id' => $id, 'user_id' => Auth::user()->id, 'state'=>'blank' ] );
      }

      Trace::create([ 
       'user_id' => Auth::user()->id,
       'action'  => trans("logs.unconfirmed_as_ok"),
       'object_type' => 'holdingsset',
       'object_id' => $model->holdingsset->id,
      ]);

    }
}