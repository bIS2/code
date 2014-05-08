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
      $ids = $model->holdingsset->holdings()->lists('id');
      foreach ($ids as $id) {
	      State::create( [ 'holding_id' => $id, 'user_id' => $user_id, 'state'=>'incorrected' ] );
      }

      Holdingsset::find($holdingsset_id)
      ->update([ 
       'state' => 'incorrected'
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

      // change to the blank state
      $ids = $model->holdingsset->holdings()->lists('id');
      foreach ($ids as $id) {
  	      State::create( [ 'holding_id' => $id, 'user_id' => $user_id, 'state'=>'blank' ] );
      }  

      Holdingsset::find($holdingsset_id)
      ->update([ 
       'state' => 'blank'
      ]);

    }
}