<?php
/*
* Observer events (created, deleted) occur Holdinsset model. Perform necessary actions after a specific event occurs on the model.
*
*/
class HoldingssetObserver {

    public function created($model) {
    	// Trace::create([ 'user_id'=>Auth::user(),'action'=>trans('logs.create-Holdings Set') ]);
    }

    public function updated($model, $query)
    {
  		Trace::create([ 
  			'user_id'	=> Auth::user()->id,
  			'action'	=> trans("logs.algo",['name'=>$model->sys1]),
  			'object_type' => 'holdingsset',
  			'object_id' => $model->id,
  		]);
    }

    public function saved($model)
    {
        //
    }

}