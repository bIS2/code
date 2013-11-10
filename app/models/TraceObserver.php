<?php

class TraceObserver {

    public function created($model) {

    	$klass=strtolower( get_class($model) );
  		Trace::create([ 
  			'user_id'	=> Auth::user()->id,
  			'action'	=> trans("logs.create-$klass",['name'=>$model->name]) 
  		]);
	
    }

    public function updated($model) {

    	$klass=strtolower( get_class($model) );
    	if ($klass=='holdingsset') {
    		if ( $model->isDirty('ok') ){
		  		Trace::create([ 
		  			'user_id'	=> Auth::user()->id,
		  			'action'	=> trans("logs.holding-set-ok",['name'=>$model->name]) 
		  		]);
    		}
    	}
	
    }


    public function saved($model) {
        //
    }

}