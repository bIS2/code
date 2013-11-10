<?php

class TraceObserver {

    public function created($model) {

    	$klass=strtolower( get_class($model) );
  		Trace::create([ 
  			'user_id'	=> Auth::user()->id,
  			'action'	=> trans("logs.create-$klass",['name'=>$model->name]) 
  		]);
	
    }

    public function saved($model)
    {
        //
    }

}