<?php

class TraceObserver {

    public function created($model) {

    	$klass=strtolower( get_class($model) );
  		// Trace::create([ 
  		// 	'user_id'	=> Auth::user()->id,
    //         'action'    => trans("logs.create_$klass",['name'=>$model->name]) ,
    //         'object_type'   => strtolower( get_class($model) ), 
  		// 	'object_id'	=>  $model->id,
  		// ]);
	
    }

    public function updated($model) {

    	$klass=strtolower( get_class($model) );

    	if ($klass=='holdingsset') {
    		if ( $model->isDirty('ok') && ($model->ok==1) ){
		  		Trace::create([ 
		  			'user_id'	=> Auth::user()->id,
		  			'action'	=> trans("logs.holdingset_change_to_ok",['name'=>$model->sys1]) 
		  		]);
    		} else {
		  		Trace::create([ 
		  			'user_id'	=> Auth::user()->id,
		  			'action'	=> trans("logs.holdingset_change_to_ko",['name'=>$model->sys1]) 
		  		]);
    		}
    	} 

    	if ( $klass == 'holding' ) {
    		if ( $model->isDirty('ok') && ($model->ok==1) ){
		  		Trace::create([ 
		  			'user_id'	=> Auth::user()->id,
		  			'action'	=> trans("logs.holding_change_to_ok",['name'=>$model->sys1]) 
		  		]);
    		} else {
		  		Trace::create([ 
		  			'user_id'	=> Auth::user()->id,
		  			'action'	=> trans("logs.holding_change_to_ko",['name'=>$model->sys1]) 
		  		]);
    		}
    	}
	
    }


    public function saved($model) {
        //
    }

}