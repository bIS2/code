<?php

class IncorrectObserver {


    public function created($model) {

			$user_id = Auth::user()->id;
      $holdingsset_id = $model->holdingsset->id;

      Trace::create([ 
       'user_id' => $user_id,
       'action'  => trans("logs.marked_as_incorrect"),
       'object_type' => 'holdingsset',
       'object_id' =>  $holdingsset_id
      ]);

      // change to incorrected state
      foreach ($model->holdingsset->holdings()->lists('id') as $id) {
	      State::create( [ 'holding_id' => $id, 'user_id' => $user_id, 'state'=>'incorrrected' ] );
      }

      Holdingsset::find($holdingsset_id)
      ->update([ 
       'state' => 'incorrect'
      ]);

    }

    public function deleted($model) {

			$user_id = Auth::user()->id;
			$holdingsset_id = $model->holdingsset->id;

      Trace::create([ 
       'user_id' => $user_id,
       'action'  => trans("logs.unmarked_as_incorrect"),
       'object_type' => 'holdingsset',
       'object_id' => $holdingsset_id
      ]);

      // change to the previus state
      foreach ($model->holdingsset->holdings()->lists('id') as $id) {
        // var_Holding::find($id)->states()->count() > 0
        // if (Holding::find($id)->states()->count() > 0) {
        // 	$prev_state = Holding::find($id)->states()->last()->state;
  	      State::create( [ 'holding_id' => $id, 'user_id' => $user_id, 'state'=>'incorrect' ] );
        // }
      }  

      Holdingsset::find($holdingsset_id)
      ->update([ 
       'state' => 'blank'
      ]);

    }
}