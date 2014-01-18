<?php

class NoteObserver {

  public function created($model) {
  	// Ok::whereHoldingId($model->holding_id)->delete();

  	$state = State::firstOrNew([ 'holding_id' => $model->holding_id, 'state'=>'annotated' ]);
  	$state->user_id = Auth::user()->id;
  	$state->save();

  }
}