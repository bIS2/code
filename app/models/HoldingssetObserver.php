<?php

class HoldingssetObserver {

    public function created($model) {
    	// Trace::create([ 'user_id'=>Auth::user(),'action'=>trans('logs.create-Holdings Set') ]);
    }

    public function updated($model, $query)
    {
    	// var_dump($model);
    	// var_dump($model['attributes']);
    	// var_dump($model['original']);
    	// die();
    	// die(var_dump(array_diff($model['attributes'], $model['original'])));
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