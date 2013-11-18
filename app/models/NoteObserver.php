<?php

class NoteObserver {

  public function created($model) {
  	Ok::whereHoldingId($model->holding_id)->delete();
  }
}