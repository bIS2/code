<?php

class StateObserver {

  public function created($model) {
  	Holding::find($model->holding_id)->update([ 'state' => $model->state ]);
  }

}