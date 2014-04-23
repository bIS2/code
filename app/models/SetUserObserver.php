<?php

class SetUserObserver {

  public function creating($model) {
  	$model->user_id = Auth::user()->id;
  }

}