<?php

class OkObserver {

  public function created($model) {

  	if ($model->state=='ok')
	  	Note::whereHoldingId($model->holding_id)->delete();

  }

}