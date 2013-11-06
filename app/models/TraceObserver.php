<?php

class TraceObserver {

    public function created($model) {

    	$klass=get_class($model);
    	if ($klass=='Group') 
    		Trace::create([ 
    			'user_id'=>Auth::user()->id,
    			'action'=>trans('logs.create_group',['name'=>$model->name]) 
    		]);

    	if ($klass=='Hlist') 
    		Trace::create([ 
    			'user_id'=>Auth::user()->id,
    			'action'=>trans('logs.create_hlist',['name'=>$model->name]) 
    		]);
    	
    }

    public function saved($model)
    {
        //
    }

}