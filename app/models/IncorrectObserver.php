<?php

class IncorrectObserver {

    public function created($model) {
      Trace::create([ 
       'user_id' => Auth::user()->id,
       'action'  => trans("logs.marked_as_incorrect"),
       'object_type' => 'holdingsset',
       'object_id' => $model->holdingsset->id,
      ]);
    }

    public function deleted($model) {
      Trace::create([ 
       'user_id' => Auth::user()->id,
       'action'  => trans("logs.unmarked_as_incorrect"),
       'object_type' => 'holdingsset',
       'object_id' => $model->holdingsset->id,
      ]);
    }
}