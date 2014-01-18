<?php

class StateObserver {

  public function created($model) {
  	Holding::find($model->holding_id)->update([ 'state' => $model->state ]);

  	//if state==ok delete all annotated state 
  	if ($model->state=='ok' && State::whereHoldingId($model->holding_id)->whereState('annotated' )->exists() )
	  	State::whereHoldingId($model->holding_id)->andWhereState('annotated' )->delete();

  	//if state is 'annotated' delete ok state 
	if ($model->state=='annotated' && State::whereHoldingId($model->holding_id)->whereState('ok' )->exists())
	  	State::whereHoldingId($model->holding_id)->andWhereState('ok' )->delete();

  }

}