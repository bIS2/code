<?php

class ConfirmObserver {

    public function created($model) {
      Trace::create([ 
       'user_id' => Auth::user()->id,
       'action'  => trans("logs.confirmed_as_ok"),
       'object_type' => 'holdingsset',
       'object_id' => $model->holdingsset->id,
      ]);
    }

    public function deleted($model) {
      Trace::create([ 
       'user_id' => Auth::user()->id,
       'action'  => trans("logs.unconfirmed_as_ok"),
       'object_type' => 'holdingsset',
       'object_id' => $model->holdingsset->id,
      ]);
    }
}