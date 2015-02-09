<?php
/*
* Observer events (created, deleted) occur Holdingsset model. Perform necessary actions after a specific event occurs on the model.
*
*/
class HoldingssetObserver {

    public function created($model) {
    	// Trace::create([ 'user_id'=>Auth::user(),'action'=>trans('logs.create-Holdings Set') ]);
    }

    public function updated($model, $query)
    {
      // var_dump($model['attributes']);
      $id = (Auth::user()->id == null) ? 76 : Auth::user()->id;
      // var_dump($id); die();
  		Trace::create([ 
  			'user_id'	=> $id,
  			'action'	=> trans("logs.updated",['name'=>$model->sys1]),
  			'object_type' => 'holdingsset',
  			'object_id' => $model->id,
  		]);
    }

    public function saved($model)
    {
        //
    }

}