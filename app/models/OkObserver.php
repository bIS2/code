<?php

class OkObserver {

  public function created($model) {
  	Note::whereHoldingId($model->holding_id)->delete();
  }

}