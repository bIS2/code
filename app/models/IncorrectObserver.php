<?php

class IncorrectObserver {


    public function created($model) {

			$user_id = Auth::user()->id;

      Trace::create([ 
       'user_id' => $user_id,
       'action'  => trans("logs.marked_as_incorrect"),
       'object_type' => 'holdingsset',
       'object_id' => $model->holdingsset->id,
      ]);

      // change to incorrected state
      foreach ($model->holdingsset->holdings()->lists('id') as $id) {
	      State::create( [ 'holding_id' => $id, 'user_id' => $user_id, 'state'=>'incorrrected' ] );

      }

    }

    public function deleted($model) {

			$user_id = Auth::user()->id;
			
      Trace::create([ 
       'user_id' => $user_id,
       'action'  => trans("logs.unmarked_as_incorrect"),
       'object_type' => 'holdingsset',
       'object_id' => $model->holdingsset->id,
      ]);

      // change to the previus state
      foreach ($model->holdingsset->holdings()->lists('id') as $id) {
      	$prev_state = Holding::find($id)->states()->last()->state;
	      State::create( [ 'holding_id' => $id, 'user_id' => $user_id, 'state'=>$prev_state ] );
      }

    }
}