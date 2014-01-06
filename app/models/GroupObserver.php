<?php

class GroupObserver {

    public function created($model) {
      Trace::create([ 
       'user_id' => Auth::user()->id,
       'action'  => trans("logs.created"),
       'object_type' => 'holdingsset',
       'object_id' => $model->id,
      ]);
    }

  public function deleted($model) {
      Trace::create([ 
       'user_id' => Auth::user()->id,
       'action'  => trans("logs.deleted"),
       'object_type' => 'holdingsset',
       'object_id' => $model->id,
      ]);
    }

}