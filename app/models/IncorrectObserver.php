<?php

class IncorrectObserver {

    public function created($model) {
      Trace::create([ 
       'user_id' => Auth::user()->id,
       'action'  => trans("logs.marked_as_incorrect"),
       'object_type' => 'holdingsset',
       'object_id' => $model->holdingsset->id,
      ]);

      // change to incorrected state
      foreach ($model->holdings()->lists('id') as $id) {
	      State::create( [ 'holding_id' => $id, 'user_id' => Auth::user()->id, 'state'=>'incorrrected' ] );

      }

    }

    public function deleted($model) {
      Trace::create([ 
       'user_id' => Auth::user()->id,
       'action'  => trans("logs.unmarked_as_incorrect"),
       'object_type' => 'holdingsset',
       'object_id' => $model->holdingsset->id,
      ]);

      // change to the previus state
      foreach ($model->holdings()->lists('id') as $id) {
      	$prev_state = Holding::find($id)->states()->last()->state;
	      State::create( [ 'holding_id' => $id, 'user_id' => Auth::user()->id, 'state'=>$prev_state ] );
      }

    }
}