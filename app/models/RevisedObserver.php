<?php

class RevisedObserver {

    public function created($model) {
      Trace::create([ 
       'user_id' => Auth::user()->id,
       'action'  => trans("logs.marked_as_revised"),
       'object_type' => 'holding',
       'object_id' => $model->holding->id,
      ]);

      // $holding_id = $model->holding->id;
      // Holdingsset::find(Holding::find($holding_id)->holdingsset_id)->update(['locked' => 1]);   
         
    }

    public function deleted($model) {
      Trace::create([ 
       'user_id' => Auth::user()->id,
       'action'  => trans("logs.unmarked_as_revised"),
       'object_type' => 'holding',
       'object_id' => $model->holding->id,
      ]);

    }

}