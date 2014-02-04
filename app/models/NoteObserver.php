<?php

class NoteObserver {

  public function created($model) {
  	// Ok::whereHoldingId($model->holding_id)->delete();
  	$holding_id = $model->holding_id;
  	// var_dump($holding_id);
  	$state = State::firstOrNew([ 'holding_id' => $holding_id, 'state'=>'annotated',  'user_id' => Auth::user()->id ]);
  	$state->save();
  }
}